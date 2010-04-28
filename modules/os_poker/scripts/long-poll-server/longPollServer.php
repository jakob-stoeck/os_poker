<?
/*
phpSocketDaemon 1.0 - httpd server demo implementation
Copyright (C) 2006 Chris Chabot <chabotc@xs4all.nl>
See http://www.chabotc.nl/ for more information

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/
class longPollServer extends socketServer {
  private $messages = array();
  private $active_users = array();
  

  public function on_timer() {
    $now = time();
    $this->dao->set_active_users($this->active_users);
    $this->active_users = array();
    foreach($this->dao->get_messages() as $uid => $messages) {
      $this->messages[$uid] = array_merge(is_array($this->messages[$uid]) ? $this->messages[$uid] : array(), $messages);
    }
  }

  public function on_accept(longPollServerClient $client) {
    // inject this and DAO into client
    $client->server = $this;
    $client->dao = $this->dao;
  }

  public function get_messages($uid) {
    $this->active_users[] = $uid;
    return is_array($this->messages[$uid]) ? $this->messages[$uid] : array();
  }

  public function flush_messages($uid) {
    unset($this->messages[$uid]);
  }

}

interface longPollDao {
  public function get_uid_for_session($session_id);
  public function get_messages();
  public function set_active_users($uids);
}

class drupalDao implements longPollDao {

  private $db;

  public function  __construct($dbconfig) {
    if(file_exists($dbconfig)) {
      include_once $dbconfig;
    }
    ini_set('mysqli.reconnect', TRUE);
    $this->db = new mysqli($dbserver, $dbuser, $dbpass, $dbname, $dbport);
    if (mysqli_connect_error()) {
      throw new Exception('Database connection error ('. mysqli_connect_errno() .') ' . mysqli_connect_error());
    }
  }

  public function get_uid_for_session($session_id) {
    $results = $this->query("SELECT uid FROM sessions WHERE sid = '". $this->db->real_escape_string($session_id). "'");
    if($results) {
      $row = $results->fetch_row();
      if($row) {
        return $row[0];
      }
      else {
        return FALSE;
      }
    }
    else {
      return FALSE;
    }
  }

  public function get_messages() {
    $messages = array();
    $this->query("LOCK TABLES polling_messages WRITE");
    $results = $this->query("SELECT uid, message FROM polling_messages");
    if($results) {
      while($row = $results->fetch_row()) {
        $messages[$row[0]][] = unserialize($row[1]);
      }
    }
    $this->query('TRUNCATE TABLE polling_messages');
    $this->query("UNLOCK TABLES");
    return $messages;
  }

  public function set_active_users($uids) {
    $now = time();
    foreach($uids as $uid) {
      $this->query("INSERT INTO polling_users VALUES (". $uid .", ". $now .") ON DUPLICATE KEY UPDATE timestamp = ". $now);
    }
    $this->query("DELETE FROM polling_users WHERE timestamp < " . time() - 60);
  }

  private function query($query) {
    $this->db->ping();
    $results = $this->db->query($query);
    if($this->db->error) {
      throw new Exception('Database error ('. $this->db->errno .') ' . $this->db->error);
    }
    return $results;
  }
}

class longPollServerClient extends socketServerClient {
	private $max_total_time = 3600;
	private $max_idle_time  = 60;
	private $keep_alive = false;
	private $accepted;
	private $last_action;

  /**
   * @var mixed The uid of the user this client is currently waiting messages
   * for. If === FALSE, then the client is not waiting for any messages.
   */
  private $uid = FALSE;

  /**
   * @var longPollServer The server used to serve this client.
   */
  public $server;

  /**
   * @var longServerDao DAO component to access data (from Drupal's DB).
   */
  public $dao;

	private function handle_request($request)
	{
		if (!$request['version'] || ($request['version'] != '1.0' && $request['version'] != '1.1')) {
			// sanity check on HTTP version
			$header  = 'HTTP/'.$request['version']." 505 HTTP Version not supported\r\n";
			$output  = '505: HTTP Version not supported';
    }
    elseif (!isset($request['method']) || $request['method'] != 'get') {
			// sanity check on request method (only get is allowed)
      $header  = 'HTTP/'.$request['version']." 405 Method Not Allowed\r\n";
			$output  = '405: Method Not Allowed';
    }
    else {
      if (preg_match('/SESS[a-zA-Z0-9]*=([a-zA-Z0-9]*)/', $request['cookie'], $matches)) {
        // $matches[1] is safe for db query since it is extract from ([a-zA-Z0-9]*).
        $this->uid = $this->dao->get_uid_for_session($matches[1]);
      }
      else {
        $this->uid = FALSE;
      }
      if ($this->uid) {
        $this->poll_messages();
      }
      else {
        // no uid == no authorization
        $header  = 'HTTP/'.$request['version']." 403 Forbidden\r\n";
        $output  = '403: Forbidden';
      }
    }
    if($header || $output) {
      $header .= "Content-Length: ".strlen($output)."\r\n";
      $header .=  'Date: '.gmdate('D, d M Y H:i:s T')."\r\n";
      if ($this->keep_alive) {
        $header .= "Connection: Keep-Alive\r\n";
        $header .= "Keep-Alive: timeout={$this->max_idle_time} max={$this->max_total_time}\r\n";
      } else {
        $this->keep_alive = false;
        $header .= "Connection: Close\r\n";
      }
      $this->write($header."\r\n".$output);
    }
  }

	public function on_read()
	{
		$this->last_action = time();
		if ((strpos($this->read_buffer,"\r\n\r\n")) !== FALSE || (strpos($this->read_buffer,"\n\n")) !== FALSE) {
			$request = array();
			$headers = split("\n", $this->read_buffer);
			$request['uri'] = $headers[0];
			unset($headers[0]);
			while (list(, $line) = each($headers)) {
				$line = trim($line);
				if ($line != '') {
					$pos  = strpos($line, ':');
					$type = substr($line,0, $pos);
					$val  = trim(substr($line, $pos + 1));
					$request[strtolower($type)] = strtolower($val);
				}
			}
			$uri                = $request['uri'];
			$request['method']  = strtolower(substr($uri, 0, strpos($uri, ' ')));
			$request['version'] = substr($uri, strpos($uri, 'HTTP/') + 5, 3);
			$uri                = substr($uri, strlen($request['method']) + 1);
			$request['url']     = substr($uri, 0, strpos($uri, ' '));
			foreach ($request as $type => $val) {
				if ($type == 'connection' && $val == 'keep-alive') {
					$this->keep_alive = true;
				}
			}
      $this->handle_request($request);
			$this->read_buffer  = '';
		}
	}

	public function on_connect()
	{
		//echo "[httpServerClient] accepted connection from {$this->remote_address}\n";
		$this->accepted    = time();
		$this->last_action = $this->accepted;
	}

	public function on_disconnect()
	{
		//echo "[httpServerClient] {$this->remote_address} disconnected\n";
	}

	public function on_write()
	{
		if (strlen($this->write_buffer) == 0 && !$this->keep_alive) {
			$this->disconnected = true;
			$this->on_disconnect();
			$this->close();
		}
	}

	public function on_timer()
	{
    $time = time();
		$idle_time  = $time - $this->last_action;
		$total_time = $time - $this->accepted;
    if ($this->uid !== FALSE) {
      $messages_sent = $this->poll_messages($total_time > $this->max_total_time || $idle_time > $this->max_idle_time);
      if ($messages_sent) {
        $idle_time = 0;
      }
    }
		if ($total_time > $this->max_total_time || $idle_time > $this->max_idle_time) {
      $this->on_disconnect();
			$this->close();
		}
	}

  /**
   * Poll the server for messages for $this->uid. If any message is found or
   * if $timeout is TRUE writes an HTTP response (using $this->write),
   * sets $this->uid to FALSE and returns. Otherwise returns FALSE.
   *
   * @param Boolean $timeout force writting an HTTP response
   * @return Booelan True if an HTTP has been wrote.
   */
  private function poll_messages($timeout = FALSE) {
    if($this->uid) {
      // get messages for this client
      $messages = $this->server->get_messages($this->uid);
      if ($messages || $timeout) {
        // if any message is available, write a JSON response
        $header  = "HTTP/{$request['version']} 200 OK\r\n";
        $output = json_encode(array(
          'errorMsg' => null,
          'error' => false,
          'messages' => $messages ? $message : array(),
        ));
        $header .= "Content-Length: ".strlen($output)."\r\n";
        $header .= 'Date: '.gmdate('D, d M Y H:i:s T')."\r\n";
        $header .= "Cache-Control: no-store, no-cache, must-revalidate\r\n";
        $header .= "Expires: Thu, 01 Jan 1970 00:00:00 GMT\r\n";
        $header .= "Content-type: application/json\r\n";
        if ($this->keep_alive) {
          $header .= "Connection: Keep-Alive\r\n";
          $header .= "Keep-Alive: timeout={$this->max_idle_time} max={$this->max_total_time}\r\n";
        } else {
          $this->keep_alive = false;
          $header .= "Connection: Close\r\n";
        }
        $this->write($header."\r\n".$output);
        $this->server->flush_messages($this->uid);
        $this->uid = FALSE;
        return TRUE;
      }
    }
    return FALSE;
  }
}

