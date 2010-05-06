<?php
/*
os-poker-poll server
Copyright (C) 2010 Pierre Buyle <pierre@buyle.org>

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
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../longPollServer.php';
require_once 'testableLongPollServerClient.php';

/**
 * Test class for longPollServer.
 * Generated by PHPUnit on 2010-04-28 at 08:14:28.
 */
class longPollServerTest extends PHPUnit_Framework_TestCase {
  /**
   * @var    longPollServer
   * @access protected
   */
  protected $object;

  protected $dao;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   *
   * @access protected
   */
  protected function setUp() {
    $this->object = new longPollServer('longPollServerClient');
    $this->object->dao = $this->dao = $this->getMock('longPollDao');
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   *
   * @access protected
   */
  protected function tearDown() {
  }

  /**
   * @dataProvider messagesProvider
   */
  public function testOnTimer($messages, $active_users) {
    //DAO's get_message is called once
    $this->dao->expects($this->once())
      ->method('get_messages')
      ->will($this->returnValue($messages));

    //DAO's set_active_users is callid once with the expected active users
    $this->dao->expects($this->once())
      ->method('set_active_users')
      ->with($this->equalTo(array_values($active_users)));

    //For the server, active users are those for which get_messages has been
    //called since last on_timer call.
    foreach($active_users as $uid) {
      $this->object->get_messages($uid);
    }
    $this->object->on_timer();
    foreach($messages as $uid => $m) {
      $this->assertEquals(isset($m) ? $m : array(), $this->object->get_messages($uid), 'Messages from DAO are returned by longPollServer::get_messages after longPollServer::on_timer.');
    }
  }

  /**
   * @dataProvider messagesProvider
   */
  public function testFlushMessages($messages) {
    $active_users = array_keys($messages);
    //DAO's get_message is called once
    $this->dao->expects($this->exactly(2))
      ->method('get_messages')
      ->will($this->returnValue($messages));

    //DAO's set_active_users is callid once with the expected active users
    $this->dao->expects($this->exactly(2))
      ->method('set_active_users')
      ->with($this->equalTo(array_values($active_users)));
      
    //For the server, active users are those for which get_messages has been
    //called since last on_timer call.
    foreach($active_users as $uid) {
      $this->object->get_messages($uid);
    }
    $this->object->on_timer();
    foreach($messages as $uid => $m) {
      $this->object->flush_messages($uid);
      $this->assertEquals(array(), $this->object->get_messages($uid), 'Messages are not returned after longPollServer::flush_messages.');
    }
    $this->object->on_timer();
    foreach($messages as $uid => $m) {
      $this->assertEquals(isset($m) ? $m : array(), $this->object->get_messages($uid), 'Only messages from DAO are returned by longPollServer::get_messages after longPollServer::flush_messages and longPollServer::on_timer.');
      $this->object->flush_messages($uid);
      $this->assertEquals(array(), $this->object->get_messages($uid), 'Messages are not returned after longPollServer::flush_messages.');
    }
  }
  
  public function testMessagesAreDiscardedAfter20OnTimer() {
    //DAO's get_message is called once
    $this->dao->expects($this->exactly(30))
      ->method('get_messages')
      ->will($this->returnValue(array(1 => array('foo'))));

    //DAO's set_active_users is callid once with the expected active users
    $this->dao->expects($this->exactly(30))
      ->method('set_active_users');
      
    for($i = 0; $i < 30; $i++) {
      $this->object->on_timer();
      $this->assertEquals(min($i+1, 20), count($this->object->get_messages(1)));
    }
  }

  public function testOnAccept() {
    $client = $this->getMock('testableLongPollServerClient');
    $this->object->on_accept($client);
    $this->assertEquals($this->object, $client->server, "longPollServer::on_accept injects server into client.");
    $this->assertEquals($this->dao, $client->dao, "longPollServer::on_accept injects server's DAO into client.");
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
        array(1, 2, 3),
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
?>
