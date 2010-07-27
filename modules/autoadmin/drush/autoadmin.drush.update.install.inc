<?php
// $Id: autoadmin.drush.update.install.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Handles "drush autoadmin update install" command.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Updates .install file.
 * 
 * @return array
 */
function autoadmin_drush_update_install() {
  $args = func_get_args();
  autoadmin_drush_args_module_then_tables($args, $module, $tables, TRUE);
  $install_schemas =  autoadmin_drush_get_schemas($module);
  $tables = array_merge(array_keys($install_schemas), $tables);
  $code = '';
  foreach ($tables as $table) {
    $inspected_schema = autoadmin_get_inspected_schema($table, FALSE);
    if (!empty($install_schemas[$table])) {
      $inspected_schema = autoadmin_drush_add_schema_descriptions($install_schemas[$table], $inspected_schema);
    }
    $inspected_schema = autoadmin_drush_sort_schema($inspected_schema);
    if (!empty($inspected_schema)) {
      $code .= autoadmin_drush_theme_update_hook_schema($table, $inspected_schema);
    }
  }
  $hook_schema_code = autoadmin_drush_theme_update_hook_schema_function($module, $install_schemas, $code);
  $hook_schema_code = autoadmin_drush_replace_hook_schema($module, $hook_schema_code);
  autoadmin_drush_output_file($module, 'install', $hook_schema_code);
  drush_core_cache_clear();
}

/**
 * Merge descriptions.
 * 
 * @param array $source
 * @param array $target
 * @return array
 */
function autoadmin_drush_add_schema_descriptions($source, $target) {
  if (!empty($source['description'])) {
    $target['description'] = $source['description'];
  }
  if (empty($target['description'])) {
    $target['description'] = 'TODO: please describe this schema!';
  }
  foreach ($source['fields'] as $key => $field) {
    if (empty($target['fields'][$key])) {
      continue;
    }
    if (!empty($field['description']) && !empty($target['fields'][$key])) {
      $target['fields'][$key]['description'] = $field['description'];
    }
  }
  foreach ($target['fields'] as $key => $field) {
    if (empty($target['fields'][$key]['description'])) {
      $target['fields'][$key]['description'] = 'TODO: please describe this field!';
    }
  }
  unset($target['name']);
  return $target;
}

/**
 * Replace only code for hook_autoadmin_schema() function.
 * 
 * @param string $module
 * @param string $hook_schema_code
 * @return string
 */
function autoadmin_drush_replace_hook_schema($module, $hook_schema_code) {
  $file = AUTOADMIN_MODULESPATH . "/$module/$module.install";
  $content = autoadmin_drush_load_file($file);
  $pattern = "/function {$module}_schema\(\) \{.*return.*\}/Uis";
  if (!preg_match($pattern, $content, $matches)) {
    drush_die(t('No hook_schema found in file !file.', array('!file' => $file)));
  }
  $content = preg_replace($pattern, $hook_schema_code, $content);
  return $content;
}