#!/usr/bin/php -f
<?
/*
phpSocketDaemon 1.0
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

ini_set('mbstring.func_overload', '0');
ini_set('output_handler', '');
error_reporting(E_ALL | E_STRICT);
@ob_end_flush();
set_time_limit(0);
include("socket.php");
include("longPollServer.php");

if($argc < 2) {
  exit("Usage: $argv[0] <config.php> [port] [address]\n");
}
if(!file_exists($argv[1])) {
  exit("Invalid configuration file: $argv[1]\n");
}
else {
  include($argv[1]);
}

$bind_port = ($argc >= 3 ? $argv[2] : 8081);
$bind_addess = ($argc >= 4 ? $argv[3] : '');

$daemon = new socketDaemon();
$server = $daemon->create_server('longPollServer', 'longPollServerClient', 0, 8081);
$server->dao = new drupalDao($dbserver, $dbuser, $dbpass, $dbname, (int) $dbport);
$daemon->process();
