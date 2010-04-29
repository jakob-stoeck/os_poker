<?php
/**
 * Testable subclass of longPollServerClient.
 *
 * Overrides methods usind socket related functions.
 *
 * @author pbuyle
 */
class testableLongPollServerClient extends longPollServerClient {
  public $output = '';

  public function __construct() {}

  public function do_write() {
    $this->output .= $this->write_buffer;
    $this->write_buffer = '';
    $this->on_write();
    return true;
  }
}
