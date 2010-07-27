<?php
// $Id: autoadmin.drush.generate.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Handles "drush autoadmin generate" command.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Generate .install and .autoadmin.inc files.
 */
function autoadmin_drush_generate() {
  $args = func_get_args();
  autoadmin_drush_args_module_then_tables($args, $module, $tables);
  call_user_func_array('autoadmin_drush_generate_install', $args);
  autoadmin_drush_generate_schemas($module);
}