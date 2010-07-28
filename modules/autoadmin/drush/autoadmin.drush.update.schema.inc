<?php
// $Id: autoadmin.drush.update.schema.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Handles "drush autoadmin update schema" command.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Update .autoadmin.inc files.
 */
function autoadmin_drush_update_schema() {
  $modules = func_get_args();
  autoadmin_drush_check_modules_exists($modules);
  autoadmin_drush_update_schemas($modules);
  cache_clear_all('autoadmin', 'cache', '*');
  menu_rebuild();
}

/**
 * Update schemas in modules.
 * 
 * @param array $modules
 */
function autoadmin_drush_update_schemas($modules) {
  foreach ($modules as $module) {
    module_load_include('inc', $module, "$module.autoadmin");
    $autoadmin_schemas = module_invoke($module, 'autoadmin_schema');
    $install_schemas = autoadmin_drush_get_schemas($module);
    $schemas = autoadmin_drush_merged_schemas($autoadmin_schemas, $install_schemas);
    $file_code = autoadmin_drush_generate_schema_file($module, $schemas);
    autoadmin_drush_output_file($module, 'autoadmin.inc', $file_code);
  }
}

/**
 * Merge schemas.
 * 
 * @param array $autoadmin_schemas
 * @param array $install_schemas
 * @return array
 */
function autoadmin_drush_merged_schemas($autoadmin_schemas, $install_schemas) {
  foreach ($autoadmin_schemas as $table => &$autoadmin_schema) {
    if (empty($install_schemas[$table])) {
      unset($autoadmin_schemas[$table]);
      continue;
    }
    $install_schema = $install_schemas[$table];
    foreach ($autoadmin_schema['fields'] as $key => $field) {
      if (empty($install_schema['fields'][$key])) {
        unset($autoadmin_schema['fields'][$key]);
        foreach ($autoadmin_schema['relations'] as $key => $relation) {
          if ($relation['local_key'] == $key) {
            unset ($autoadmin_schema['relations'][$key]);
          }
        }
      }
    }
  }
  foreach ($install_schemas as $table => $install_schema) {
    $autoadmin_new_schema = autoadmin_drush_auto_autoname($install_schema, $table, FALSE);
    if (!empty($autoadmin_schemas[$table])) {
      $autoadmin_schemas[$table] = autoadmin_drush_sort_schema(autoadmin_array_merge_recursive_distinct($autoadmin_new_schema, $autoadmin_schemas[$table]));
    } else {
      $autoadmin_schemas[$table] = $autoadmin_new_schema;
    }
  }
  return $autoadmin_schemas;
}