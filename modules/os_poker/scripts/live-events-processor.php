#!/usr/bin/php -f
<?php
set_time_limit(0);
chdir(realpath(dirname(__FILE__) . '/..'));
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once(drupal_get_path('module', 'os_poker') . "/poker.class.php");
require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");

while(sleep(3) !== FALSE) {
  CScheduler::instance()->ProcessLiveEvents();
}