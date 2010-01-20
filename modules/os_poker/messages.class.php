<?php // -*- mode: php; tab-width: 2 -*-
//
//    Copyright (C) 2009, 2010 Pokermania
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//


require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");

/*
**
*/

class CMessage implements ITask
{
	
	/*
	** ITask 
	*/
	
	public function Run($context_user, $arguments)
	{   
		if ($arguments)
		{
			CMessageSpool::instance()->PushMsg($arguments);
		}
	}
	
	public function Type() { return get_class($this); }
	public function MaxInstances() { return -1; }
	public function AlwaysVisible() { return FALSE; }
	
}

/*
**
*/

class CDelayMessage implements ITask
{
	
	/*
	** ITask 
	*/
	
	public function Run($context_user, $arguments)
	{   
		if ($arguments)
		{
			CScheduler::instance()->RegisterTask(new CMessage(), $context_user->uid, 'live', "-1 day", $arguments);
		}
	}
	
	public function Type() { return get_class($this); }
	public function MaxInstances() { return -1; }
	public function AlwaysVisible() { return FALSE; }
}

/*
**
*/

class CStaticMessage implements ITask
{
	
	/*
	** ITask 
	*/
	
	public function Run($context_user, $arguments) { }
	public function Type() { return get_class($this); }
	public function MaxInstances() { return -1; }
	public function AlwaysVisible() { return TRUE; }
	
}

/*
**
*/


class CMessageSpool
{
	private static 	$_instance;
	private			$_messages;
	
	/*
	**
	*/
	
	public static function instance()
	{
	    if (!self::$_instance instanceof self)
		{ 
			self::$_instance = new self;
	    }
	    return self::$_instance;
	}
	
	/*
	**
	*/
	
	protected function __construct()
	{
		$this->_messages = array();
	}

	/*
	**
	*/
	
	public function PushMsg($msg)
	{
		if (is_array($msg))
		{
			$this->_messages []= $msg;
		}
	}
	
	public function Get()
	{
		return $this->_messages;
	}
		
	public function Flush()
	{
		$this->_messages = array();
	}
	
	public function SendMessage($targetUid, $args) //Wait for symbol, text, link
	{
		$current_user = CUserManager::instance()->CurrentUser();
		
		if ($current_user && $current_user->uid != 0)
		{
			if (isset($args["text"]) && isset($args["symbol"]))
			{
				if (!isset($args["tags"]))
					$args["tags"] = array();
				if (!isset($args["links"]))
					$args["links"] = NULL;
			
				$res = CScheduler::instance()->RegisterTask(new CStaticMessage(), $targetUid, 'inbox', "+2 week", array(
          "type" => "os_poker_msg",
          "body" => array(
              "text" => $args["text"],
              "links" => $args["links"],
              "tags"  => $args["tags"],
              "sender" => $current_user->profile_nickname,
              "senderPix" => $current_user->picture,
              "symbol" => $args["symbol"],
          ),
        ));
				return ($res > 0);
			}
		}
		return FALSE;
	}
	
	public function SendInstantMessage($args, $targetUid = NULL) //text
	{
		$current_user = CUserManager::instance()->CurrentUser();
		
		if ($current_user && $current_user->uid != 0)
		{
			if (isset($args["text"]))
			{
				if ($targetUid == NULL)
					$targetUid = $current_user->uid;
			
				CScheduler::instance()->RegisterTask(new CMessage(), $targetUid, array('live'), "-1 day", array(
            'type' => 'os_poker_imsg',
            'body' => array(
              'text' => $args['text'],
              'title' => isset($args['title']) ? $args['title'] : t('Notification'),
            )
        ));
			}
		}
	}

}
	
?>