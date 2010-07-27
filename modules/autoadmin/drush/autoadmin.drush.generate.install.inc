<?php
// $Id: autoadmin.drush.generate.install.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Handles "drush autoadmin generate install" command.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Generate .install file.
 */
function autoadmin_drush_generate_install() {
  $args = func_get_args();
  autoadmin_drush_args_module_then_tables($args, &$module, $tables);
  $code = '';
  foreach ($tables as $table) {
    $schema = autoadmin_get_inspected_schema($table);
    $code .= autoadmin_drush_theme_install_schema($table, $schema);
  }
  autoadmin_drush_output_file($module, 'install', autoadmin_drush_theme_install_file($module, $tables, $code));
}