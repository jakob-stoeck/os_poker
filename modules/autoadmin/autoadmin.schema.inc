<?php
// $Id: autoadmin.schema.inc,v 1.3 2009/12/07 13:52:20 rune Exp $

/**
 * @file
 * Contains functions for interfacing with schemas.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Retrive schema from cache.
 *
 * @param string $table
 * @param array $options
 *   The following options are supported. Default values are marked with ().
 *     'unprocessed' => TRUE/(FALSE) // Disable processing of schema and return it raw.
 * @return array/FALSE
 */
function autoadmin_schema_get_one($table, $options = array()) {
  $schemas = autoadmin_cache_function('autoadmin_schema_get_all');
  $schema = !empty($schemas[$table]) ? $schemas[$table] : FALSE;
  if (!$schema) {
    drupal_set_message(t('Table !table could not be loaded. Table !table probably has errors, and could therefore not be loaded as a relationship from another table.',
      array('!table' => $table)), 'error', FALSE);
    return FALSE;
  }
  return autoadmin_schema_load($schemas[$table], $options);
}

/**
 * Retrieves all schemas.
 *
 * @return array
 */
function autoadmin_schema_get_all() {
  $modules = module_list();
  $all_schemas = array();
  foreach ($modules as $module) {
    $schemas = autoadmin_schema_get_from_module($module);
    if (!empty($schemas)) {
      foreach ($schemas as $table => &$schema) {
        if(autoadmin_schema_has_autoadmin($schema)
          && !autoadmin_validate_schema($schema, $module, $table)) {
          unset($schemas[$table]);
        }
        $schema = array_merge(autoadmin_schema_default_settings($module, $table, $schema), $schema);
      }
      $all_schemas += $schemas;
    }
  }
  return $all_schemas;
}

/**
 * Default settings for schema that are not required in hook_autoadmin_schema().
 * 
 * @param array $_
 * @param string $key
 * @param string $module
 */
function autoadmin_schema_default_settings($module, $table, $schema) {
  return array(
    'has_autoadmin' => autoadmin_schema_has_autoadmin($schema),
    'table' => $table,
    'module' => $module,
    'inline_filter' => FALSE,
    'relations' => array(),
    'alias' => autoadmin_drush_default_schema_alias($schema),
    'path' => '',
  );
}

/**
 * Get schema, do som processing and return result.
 *
 * @param array $schema
 * @param array $options
 *   The following options are supported. Default values are marked with ()
 *     'unprocessed' => (FALSE)/TRUE // Disable processing of schema and return it raw.
 * @return array
 */
function autoadmin_schema_load($schema, $options = array()) {
  $cached_schemas = autoadmin_cache_function('autoadmin_schema_get_all');
  $schemas = autoadmin_schema_get_from_module($schema['module']);
  $schema = array_merge($cached_schemas[$schema['table']], $schemas[$schema['table']]);
  if (!empty($options['unprocessed'])) {
    return $schema;
  } else {
    return _autoadmin_schema_process_schema($schema);
  }
}

/**
 * Merge schemas from hook_schema() and hook_autoadmin_schema, and return result.
 * 
 * @param string $module
 * @return array
 */
function autoadmin_schema_get_from_module($module) {
  module_load_include('autoadmin.inc', $module);
  module_load_include('install', $module);
  $autoadmin_schemas = module_invoke($module, 'autoadmin_schema');
  if (!empty($autoadmin_schemas)) {
    foreach ($autoadmin_schemas as &$autoadmin_schema) {
      $autoadmin_schema['has_autoadmin'] = TRUE;
    }
  }
  return autoadmin_array_merge_recursive_distinct(
    module_invoke($module, 'schema'), $autoadmin_schemas
    
  );
}

/**
 * Process and validate schema.
 *
 * @param array $schema
 * @param string $table
 * @return array
 */
function _autoadmin_schema_process_schema(&$schema) {
  $schema = _autoadmin_schema_process_fields($schema);
  $schema = _autoadmin_schema_process_relations($schema);
  // Which argument num to use as primary value.
  $schema['argument_num'] = substr_count($schema['path'], '/') + 1;
  return $schema;
}

/**
 * Set default values for all fields in schema.
 *
 * @param schema $schema
 * @return schema
 */
function _autoadmin_schema_process_fields($schema) {
  array_walk($schema['fields'], '_autoadmin_schema_default_field');
  return $schema;
}

/**
 * Set default value for a single field.
 *
 * @param array $schema changed by reference
 * @param string $field_key
 * @param array $field changed by reference
 */
function _autoadmin_schema_default_field(&$_, $key) {
  $_['key'] = $key;
}

/**
 * Adds relation to the field itself and grouped by relation type.
 *
 * @param array $schema changed by ref
 * @param array $field changed by ref
 */
function _autoadmin_schema_process_relations($schema) {
  foreach ($schema['relations'] as $foreign_table => &$relation) {
    $relation['foreign_table'] = $foreign_table;
    $foreign_schema = autoadmin_schema_get_one($foreign_table, array('unprocessed' => TRUE));
    $relation['foreign_alias'] = $foreign_schema['alias'];
    $schema['relations_by_type'][$relation['type']][] = &$relation;
    _autoadmin_schema_default_relation($relation);
  }
  return $schema;
}

/**
 * Add default relation settings to the field.
 *
 * @param array $relation changed by ref
 */
function _autoadmin_schema_default_relation(&$relation) {
  $relation = array_merge(array(
      'cascading_delete' => TRUE,
    ),
    $relation
  );
}

/**
 * Returns the a schemas primary field
 * @param array $schema
 * @return array
 */
function autoadmin_schema_get_primary_field($schema) {
  $primary_field = $schema['primary key'][0];
  return $schema['fields'][$primary_field];
}

/**
 * Check if schema has autoadmin settings
 * 
 * @param array $schema
 * @return bool
 */
function autoadmin_schema_has_autoadmin($schema) {
  return !empty($schema['has_autoadmin']);
}