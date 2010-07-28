<?php
// $Id: autoadmin.drush.update.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Handles "drush autoadmin update" command.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Updates .install and .autoadmin.inc files.
 *
 * @global array $argv
 */
function autoadmin_drush_update() {
  $args = func_get_args();
  autoadmin_drush_args_module_then_tables($args, $module, $tables, TRUE);
  global $argv;
  $update_install_args = array();
  foreach ($argv as $arg) {
    $update_install_args[] = $arg;
    if ($arg == 'update') {
      $update_install_args[] = 'install';
    }
  }
  $update_schema_args = array();
  foreach ($argv as $arg) {
    if ($arg[0] == '-') {
      $update_schema_args[] = $arg;
    }
  }
  $update_install_args[0] = 'drush';
  system(implode(' ', $update_install_args));
  system("drush autoadmin update schema $module " . implode(' ', $update_schema_args));
}