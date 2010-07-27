<?php
// $Id: autoadmin.drush.auto.inc,v 1.2 2009/12/07 13:52:20 rune Exp $

/**
 * @file
 * Various helper functions. Notice these functions are not namespaced.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 * @todo This file is pretty messy. Clean it up.
 */

/**
 * Try to guess correct titles and autoadmin_types from table and field names.
 *
 * @param array $schema
 * @param string $table
 * @param bool $overwrite
 * @return array
 */
function autoadmin_drush_auto_autoname($schema, $table, $overwrite = TRUE) {
  $autoadmin_schema = autoadmin_drush_auto_get_default_schema($schema, $table, $overwrite);
  if (empty($schema['title'])) {
    $autoadmin_schema['title'] = autoadmin_text_from_table($table);
  }
  if (empty($schema['title_plural'])) {
    $autoadmin_schema['title_plural'] = autoadmin_text_pluralize($autoadmin_schema['title']);
  }
  foreach ($schema['fields'] as $key => $field) {
    $autoadmin_schema['fields'][$key]['autoadmin_type'] = autoadmin_drush_auto_get_autoadmin_type($schema, $field, $key);
    if (empty($schema['fields'][$key]['title'])) {
      $autoadmin_schema['fields'][$key]['title'] = autoadmin_text_from_field($key);
    }
    if (empty($schema['fields'][$key]['title_plural'])) {
      $autoadmin_schema['fields'][$key]['title_plural'] = autoadmin_text_pluralize($autoadmin_schema['fields'][$key]['title']);
    }
    foreach ($field as $setting_key => $setting) {
      if (!in_array($setting_key, array('title', 'title_plural', '#form'))) {
        unset($autoadmin_schema['fields'][$key][$setting_key]);
      }
    }
  }
  autoadmin_drush_auto_find_relations($autoadmin_schema, $table);
  return $autoadmin_schema;
}

/**
 * Default schema settings.
 *
 * @param array $schema
 * @param string $table
 * @param bool $overwrite
 * @return array
 */
function autoadmin_drush_auto_get_default_schema($schema, $table, $overwrite = TRUE) {
  $default_schema = array(
      'path' => "admin/content/$table",
      'alias' => autoadmin_drush_default_schema_alias($schema),
      'title' => 'TODO: please write title!',
      'title_plural' => 'TODO: please write title in plural!',
      'inline_filter' => FALSE,
      'fields' => array(),
      'relations' => array(),
  );
  if ($overwrite) {
    return $default_schema;
  }
  else {
    foreach ($default_schema as $key => $value) {
      if (!isset($schema[$key])) {
        $schema[$key] = $value;
      }
    }
  }
  return $schema;
}

/**
 * Returns the autoadmin type for a field.
 *
 * @param array $schema
 * @param array $field
 * @return string
 */
function autoadmin_drush_auto_get_autoadmin_type($schema, $field, $key) {
  // Change per name of field.
  switch ($key) {
    case $schema['primary key'][0]:
      return 'primary_key';
    case 'weight':
      return 'weight';
  }
  // Change into bool if its a tiny int.
  if ($field['type'] == 'int' && !empty($field['size']) && $field['size'] == 'tiny') {
    return 'bool';
  }
  // Default to field type.
  return $field['type'];
}

/**
 * Search for relations based on naming conventions.
 * 
 * @param array $schema
 * @param string $schema_table
 */
function autoadmin_drush_auto_find_relations(&$schema, $schema_table) {
  $inspected_schemas = schema_invoke('inspect');
  $names = autoadmin_drush_auto_get_convention_names($inspected_schemas);
  $relations = array();
  foreach($schema['fields'] as $field_name => &$field) {
    if(!empty($names[$field_name])) {
      $schema['relations'][$names[$field_name]['table']] = array(
        'type' => 'has_one',
        'local_key' => $field_name,
        'foreign_key' => $names[$field_name]['key'],
      );
      $field['autoadmin_type'] = 'has_one';
    }
  }
  $pk = $inspected_schemas[$schema_table]['primary key'][0];
  $name = $schema_table . '_' . $pk;
  foreach ($inspected_schemas as $i_table => $i_schema) {
    foreach($i_schema['fields'] as $field_name => $i_field) {
      if ($field_name == $name) {
        $schema['relations'][$i_table] = array(
          'type' => 'has_many',
          'local_key' => $i_schema['primary key'][0],
          'foreign_key' => $field_name,
        );
      }
    }
  }
}

/**
 * Returns names of all possible foreign_table names based on naming conventions.
 * 
 * @param array $schemas
 * @return array
 */
function autoadmin_drush_auto_get_convention_names($schemas) {
  $pk_names = array();
  foreach($schemas as $table => $schema) {
    if (!empty($schema['primary key'][0])) {
        $pk_names[$table . '_' . $schema['primary key'][0]] = array(
          'table' => $table,
          'key' => $schema['primary key'][0],
        );
    }
  }
  return $pk_names;
}