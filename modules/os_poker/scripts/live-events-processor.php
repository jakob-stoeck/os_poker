#!/usr/bin/php -f
<?php
set_time_limit(0);
if($argc > 1 && is_dir($argv[1])) {
  chdir(realpath($argv[1]));
}
elseif(isset($_ENV) && isset($_ENV['DRUPAL_ROOT'])) {
  chdir(realpath($_ENV['DRUPAL_ROOT']));
} 
else {
  chdir(realpath(dirname(__FILE__) . '/..'));
}
define('DRUPAL_ROOT', getcwd());
define('OS_POKER_SCRIPT', TRUE);

if (!file_exists('includes/bootstrap.inc')) {
  exit("Usage: $argv[0] [drupal_root]\nIf [drupal_root] is no set, script must be in drupal's script directory or DRUPAL_ROOT must be set as an environment variable.\n");
}

$_SERVER['HTTP_HOST'] = isset($_ENV['HOSTNAME']) ? $_ENV['HOSTNAME'] : 'localhost';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_SOFTWARE'] = 'PHP/curl';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/drupal6/';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/drupal6/index.php';
$_SERVER['PHP_SELF'] = '/drupal6/index.php';
$_SERVER['HTTP_USER_AGENT'] = 'Drupal command line';

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once(drupal_get_path('module', 'os_poker') . "/poker.class.php");
require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");

while(sleep(3) !== FALSE) {
  CScheduler::instance()->ProcessLiveEvents();
  foreach (drupal_get_messages() as $type => $messages) {
    foreach ($messages as $message) {
      print "[$type] $message";
    }
  } 
}
