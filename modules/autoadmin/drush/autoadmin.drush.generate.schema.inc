<?php
// $Id: autoadmin.drush.generate.schema.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Handles "drush autoadmin generate schema" command.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Generate .autoadmin.inc files.
 */
function autoadmin_drush_generate_schemas() {
  $modules = func_get_args();
  autoadmin_drush_check_modules_exists($modules);
  foreach ($modules as $module) {
    $schemas = autoadmin_drush_get_schemas($module);
    $schemas = autoadmin_drush_process_schemas($schemas);
    $file_code = autoadmin_drush_generate_schema_file($module, $schemas);
    autoadmin_drush_output_file($module, 'autoadmin.inc', $file_code);
    cache_clear_all('AUTOADMIN', 'cache', '*');
    menu_rebuild();
  }
}

/**
 * Process schemas so they will have the correct format.
 * 
 * @param array $schemas
 * @return array
 */
function autoadmin_drush_process_schemas($schemas) {
  foreach ($schemas as $table => &$schema) {
    $schema = autoadmin_drush_sort_schema(autoadmin_drush_auto_autoname($schema, $table));
  }
  return $schemas;
}

/**
 * Get the php code for the .autoadmin.inc file.
 * 
 * @param string $module
 * @param array $schemas
 * @return string
 */
function autoadmin_drush_generate_schema_file($module, $schemas) {
  $schema_code = '';
  foreach ($schemas as $table => $schema) {
    $schema_code .= autoadmin_drush_theme_schema($table, $schema);
  }
  return autoadmin_drush_theme_schema_file($module, $schemas, $schema_code);
}