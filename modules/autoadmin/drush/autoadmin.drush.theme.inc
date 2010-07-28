<?php
// $Id: autoadmin.drush.theme.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Theme functions for drush commands.
 *
 * @todo Use the theme() function instead of calling functions directly.
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Theme schema for .install file.
 * @param string $table
 * @param array $schema
 * @return string
 */
function autoadmin_drush_theme_install_schema($table, $schema) {
  return
    "  // Database schema for {{$table}} table.\n  "
    . autoadmin_drush_insert_tab(schema_phpprint_table($table, $schema))
    . "\n";
}

/**
 * Theme .install file.
 * 
 * @param string $module
 * @param array $tables
 * @param string $schemas_code
 * @return string
 */
function autoadmin_drush_theme_install_file($module, $tables, $schemas_code) {
  $tables = autoadmin_drush_theme_tables($tables, FALSE);
  return <<<EOD
<?php
// \$Id

/**
 * @file
 * Install file for $module module.
 *
 * @tables $tables
 */

/**
 * Implementation of hook_install().
 */
function {$module}_install() {
  // Create tables.
  drupal_install_schema('$module');
}

/**
 * Implementation of hook_uninstall().
 */
function {$module}_uninstall() {
  // Remove tables.
  drupal_uninstall_schema('$module');
  menu_rebuild();
}

/**
 * Implementation of hook__schema().
 */
function {$module}_schema() {
{$schemas_code}  return \$schema;
}
EOD;
}

/**
 * Theme schema for .autoadmin.inc file.
 * 
 * @param string $table
 * @param array $schema
 * @return string
 */
function autoadmin_drush_theme_schema($table, $schema) {
  $schema_code = autoadmin_drush_theme_array($schema);
  return <<<EOD
  // Auto Admin schema for {{$table}} table
  \$autoadmin_schema['$table'] = $schema_code;


EOD;
}

/**
 * Theme .autoadmin.inc file.
 * 
 * @param string $module
 * @param array $schemas
 * @param string $schema_code
 * @return string
 */
function autoadmin_drush_theme_schema_file($module, $schemas, $schema_code) {
  $tables = autoadmin_drush_theme_tables($schemas);
  return <<<EOD
<?php
// \$Id

/**
 * @file
 * Auto Admin schemas for $module module.
 *
 * @tables $tables
 */

/**
 * Implementation of hook_autoadmin_schema().
 */
function {$module}_autoadmin_schema() {
$schema_code  return \$autoadmin_schema;
}
EOD;
}

/**
 * Theme schema for update.
 *
 * @param string $table
 * @param array $schema
 * @return string
 */
function autoadmin_drush_theme_update_hook_schema($table, $schema) {
  $code = autoadmin_drush_theme_array($schema);
  return <<<EOD
  // Database schema for {{$table}} table.
  \$schema['$table'] = $code;


EOD;
}

/**
 * Theme file for update.
 * 
 * @param string $module
 * @param array $schemas
 * @param string $schemas_code
 * @return string
 */
function autoadmin_drush_theme_update_hook_schema_function($module, $schemas, $schemas_code) {
  $tables = autoadmin_drush_theme_tables($schemas);
  return <<<EOD
function {$module}_schema() {
{$schemas_code}  return \$schema;
}
EOD;
}

/**
 * Renders an array as drupal style php code.
 *
 * @param array $array
 * @return string
 */
function autoadmin_drush_theme_array($array) {
  $code = var_export($array, TRUE);
  $code = preg_replace("/=> \n *array \(/", '=> array (', $code);
  $code = preg_replace_callback("/array \(\n *0 => '(.*)',\n *\)/", 'autoadmin_drush_clean_array_cb', $code);
  $code = str_replace('array (', 'array(', $code);
  $code = preg_replace("/=> array\(\n *\)/", "=> array()", $code);
  foreach (array('description', 'title', 'title_plural') as $text) {
    $code = preg_replace_callback("/'($text)' => '(.*)',/Uis", 'autoadmin_drush_add_t_cb', $code);
  }
  $code = str_replace('=> false,', '=> FALSE,', $code);
  $code = str_replace('=> true,', '=> TRUE,', $code);
  $code = autoadmin_drush_insert_tab($code);
  return $code;
}