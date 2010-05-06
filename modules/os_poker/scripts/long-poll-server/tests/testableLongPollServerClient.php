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
