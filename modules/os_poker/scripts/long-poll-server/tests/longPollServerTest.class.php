<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) .'/../socket.php';
require_once dirname(__FILE__) .'/../longPollServer.php';

class longPollServerClientMock extends longPollServerClient {
  public $write_buffer = '';
  
  public function __construct() {}

  public function write($buffer, $length = 4096) {
    $this->write_buffer .= $buffer;
  }

}

class longPollServerTest extends PHPUnit_Framework_TestCase {

  /**
   * @dataProvider messagesProvider
   */
  public function testOnTimer($messages, $active_users) {
    $dao = $this->getMock('longPollDao');
    //DAO's get_message is called once
    $dao->expects($this->once())
      ->method('get_messages')
      ->will($this->returnValue($messages));

    //DAO's set_active_users is callid once with the expected active users
    $dao->expects($this->once())
      ->method('set_active_users')
      ->with($this->equalTo($active_users));

    $server = new longPollServer('longPollServerClient');
    $server->dao = $dao;
    //For the server, active users are those for which get_messages has been
    //called since last on_timer call.
    foreach($active_users as $uid) {
      $server->get_messages($uid);
    }
    $server->on_timer();
    foreach($messages as $uid => $m) {
      $this->assertEquals(isset($m) ? $m : array(), $server->get_messages($uid), 'Messages from DAO are returned by longPollServer::get_messages after longPollServer::on_timer.');
      $server->flush_messages($uid);
      $this->assertEquals(array(), $server->get_messages($uid), 'Messages are not returned after longPollServer::flush_messages.');
    }
  }

  public function testOnAccept() {
    $server = new longPollServer('longPollServerClient');
    $server->dao = $this->getMock('longPollDao');
    $client = $this->getMock('longPollServerClientMock');
    $server->on_accept($client);
    $this->assertEquals($server, $client->server, "longPollServer::on_accept injects server into client.");
    $this->assertEquals($server->dao, $client->dao, "longPollServer::on_accept injects server's DAO into client.");
  }


  /**
   * @dataProvider requestProvider
   */
  public function testClient($request, $response, $sessions = array()) {
    $client = new longPollServerClientMock();
    $client->read_buffer = (is_array($request) ? (implode("\n", $request)) : $request) . "\n\n";
    $client->dao = $this->getMock('longPollDao');
    $client->dao->expects($this->any())
      ->method('get_uid_for_session')
      ->will($this->returnCallback('hexdec'));
    $client->on_read();
    $this->assertEquals('', $client->read_buffer);
    $this->assertEquals(is_array($response) ? (implode("\n", $response)) : $response, $client->write_buffer);
  }

  public function requestProvider() {
    return array(
      array('GET foo HTTP/1.0','HTTP/1.0 403'),
      array('GET foo HTTP/1.1','HTTP/1.1 403'),
      array('GET foo HTTP/2.0','HTTP/2.0 505'),
      array('POST foo HTTP/1.0','HTTP/1.0 405'),
      array('POST foo HTTP/1.0','HTTP/1.0 405'),
      array("GET foo HTTP/1.0\nCookie: SESSAAA=AAA",'HTTP/1.0 200'),
    );
  }

  /**
   * Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
   */
  public function messagesProvider() {
    return array(
      array(
        //messages
        array(
          1 => explode(' ', 'Lorem ipsum dolor sit amet'),
          2 => explode(' ', 'consectetur adipisicing elit'),
          3 => explode(' ', 'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua'),
        ),
        //active_users
        array(1,2,3),
      ),
      array(
        //messages
        array(
          1 => explode(' ', 'Lorem ipsum dolor sit amet'),
          2 => explode(' ', 'consectetur adipisicing elit'),
          3 => explode(' ', 'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua'),
        ),
        //active_users
        array(1,4),
      )
    );
  }
}