<?php
// $Id: autoadmin.helper.inc,v 1.2 2009/12/07 13:52:20 rune Exp $

/**
 * @file
 * Helper functions. Please notice that as the only .inc file these functions are not namespaced
 * with the .inc file name as prefix.
 *
 * @ingroup autoadmin
 * @todo This file should go away, and all functions should be sorted under
 * appropriate .inc files.
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Assembles page descriptions.
 *
 * @param array $schema
 * @return string
 */
function autoadmin_get_page_description($schema) {
  // Get descriptions.
  $description = autoadmin_set_filter_page_description(NULL, NULL, NULL);
  // If no descriptions.
  if (empty($description)) {
    return t('Showing all !title_plural', array('!title_plural' => $schema['title_plural']));
  }
  // Otherwise show filtering values and add a 'show all' link.
  return t('Showing !title_plural where !title is !value (!link)', array(
    '!title_plural' => $schema['title_plural'],
    '!title' => $description['title'],
    '!value' => $description['value'],
    '!link' => theme('autoadmin_operations', array(array(
      'title' => t('Show all !title_plural', array('!title_plural' => $schema['title_plural'])),
      'href' => $schema['path'] . '/list',
      'query' => 'show=all'
    ))),
  ));
}

/**
 * Adds to page description for a filter where filters applies.
 * 
 * @param array $field
 * @param int $value
 */
function autoadmin_set_filter_page_description($schema, $field, $value) {
  static $description = array();
  if (empty($schema)) {
    return $description;
  }
  $relation = autoadmin_get_has_one_relation($schema, $field);
  $foreign_schema = autoadmin_schema_get_one($relation['foreign_table']);
  $description = array(
    'title' => 
      !empty($foreign_schema['title'])
      ? $foreign_schema['title']
      : $relation['foreign_table']
    ,
    'value' => db_result(db_query(
      'SELECT %s FROM {%s} WHERE %s=%d',
      $relation['foreign_alias'],
      $relation['foreign_table'],
      $relation['foreign_key'],
      $value
    ))
  );
}
/**
 * Sets session value for a specific field in the schema.
 *
 * @param array $schema
 * @param array $field
 * @param array $value
 */
function autoadmin_session_set_field_param($schema, $field, $value) {
  $_SESSION['autoadmin'][$schema['table']]['_fields'][$field['key']] = $value;
}

/**
 * Gets session value for a specific field in the schema.
 *
 * @param array $schema
 * @param array $field
 * @return mixed/NULL
 */
function autoadmin_session_get_field_param($schema, $field) {
  return
    !empty($_SESSION['autoadmin'][$schema['table']]['_fields'][$field['key']]) ?
    $_SESSION['autoadmin'][$schema['table']]['_fields'][$field['key']] :
    NULL
  ;
}

/**
 * If there is &show=all in url reset session data for schema.
 *
 * @param array $schema
 */
function autoadmin_session_check_for_reset($schema) {
  if (!empty($_GET['show']) && $_GET['show'] == 'all') {
    unset($_SESSION['autoadmin'][$schema['table']]['_fields']);
  }
}

/**
 * Clears all session data for schema.
 *
 * @param array $schema
 */
function autoadmin_session_clear_schema($schema) {
  if (!empty($_SESSION['autoadmin'][$schema['table']])) {
    unset($_SESSION['autoadmin'][$schema['table']]);
  }
}

/**
 * Sets by ref default values for an int fields length and maxlength.
 * 
 * @global String $db_type
 * @param array $field
 * @param string $size
 * @param string $maxlength
 */
function autoadmin_int_field_lengths($field, &$size, &$maxlength) {
  global $db_type;
  if ($db_type == 'mysqli' || $db_type == 'mysql') {
    $mysql_length = (string)autoadmin_get_mysql_int_length($field);
    $size = !empty($field['size']) ? $field['size'] : $mysql_length;
    $maxlength = !empty($field['maxlength']) ? $field['maxlength'] : $mysql_length;
  } else {
    $size = !empty($field['size']) ? $field['size'] : '64';
    $maxlength = !empty($field['maxlength']) ? $field['maxlength'] : '128';
  }
}

/**
 * Gets maximum length for a mysql int field based on its size or if its unsigned or not.
 *
 * @see http://help.scibit.com/Mascon/masconMySQL_Field_Types.html
 * @param array $field
 * @return int
 *   Maximum number of characters.
 */
function autoadmin_get_mysql_int_length($field) {
  if (empty($field['size'])) {
    $field['size'] = NULL;
  }
  $add = empty($field['unsigned']) ? 0 : 1;
  switch ($field['size']) {
    case 'tiny':
      return (3 + $add);
    case 'small':
      return (5 + $add);
    case 'medium':
      return (8);
    case 'normal':
      return (10 + $add);
    case 'big':
      return (20);
    default:
      return (10 + $add);
  }
}

/**
 * Returns name of callback function for field. Special rules apply to fields with relations.
 *
 * @param string $prefix
 * @param array $field
 * @return string
 */
function autoadmin_get_callback_function($prefix, $schema, $field) {
  if (!autoadmin_field_has_one_relation($schema, $field)) {
    return $prefix . $field['autoadmin_type'];
  }
  else {
    return $prefix . 'has_one';
  }
}

/**
 * Check if a field has a has_one relation.
 *
 * @todo Use native PHP search function.
 * @param array $schema
 * @param array $field
 * @return bool
 */
function autoadmin_field_has_one_relation($schema, $field) {
  if (empty($schema['relations_by_type']['has_one'])) {
    return FALSE;
  }
  foreach ($schema['relations_by_type']['has_one'] as $relation) {
    if ($relation['local_key'] == $field['key']) {
      return TRUE;
    }
  }
  return FALSE;
}

/**
 * Return the has_one relation for a field.
 *
 * @todo Use native PHP search function.
 * @param array $schema
 * @param array $field
 * @return array/FALSE
 */
function autoadmin_get_has_one_relation($schema, $field) {
  if (empty($schema['relations_by_type']['has_one'])) {
    return FALSE;
  }
  foreach ($schema['relations_by_type']['has_one'] as $relation) {
    if ($relation['local_key'] == $field['key']) {
      return $relation;
    }
  }
  return FALSE;
}

/**
 * Version of array_merge_recursive() where later arrays overwrites already existing values.
 *
 * @credit Taken from http://us2.php.net/array_merge_recursive by "michiel at synetic dot nl".
 * @return array
 */
function &autoadmin_array_merge_recursive_distinct() {
  $arrays = func_get_args();
  $array_merged = $arrays[0];
  for ($i = 1; $i < count($arrays); $i++) {
    if (is_array($arrays[$i])) {
      foreach ($arrays[$i] as $key => $val) {
        if (is_array($arrays[$i][$key])) {
          $array_merged[$key] = (!empty($array_merged[$key]) && is_array($array_merged[$key]))
						? autoadmin_array_merge_recursive_distinct($array_merged[$key], $arrays[$i][$key])
						: $arrays[$i][$key];
        }
        else {
          $array_merged[$key] = $val;
        }
      }
    }
  }
  return $array_merged;
}

/**
 * Gets the primary value from a data row and schema.
 *
 * @param array $row
 * @param array $schema
 * @return string/FALSE
 */
function autoadmin_get_pv($row, $schema) {
  return !empty($row[$schema['primary key'][0]]) ? $row[$schema['primary key'][0]] : FALSE;
}

/**
 * Checks whether a field has a filter value and returns it.
 * 
 * @param array $schema
 * @param array $field
 * @return int
 */
function autoadmin_get_filter_value($schema, $field) {
  $value = NULL;
  if (!empty($_GET[$field['key']])) {
    $value = $_GET[$field['key']];
  }
  else {
    $value = autoadmin_session_get_field_param($schema, $field);
  }
  return $value;
}

/**
 * Return schema taken from database by the schema module.
 *
 * @staticvar array $inspect
 * @param string $table
 * @return array
 */
function autoadmin_get_inspected_schema($table, $die_on_empty = TRUE) {
  static $inspect;
  if (empty($inspect)) {
    $inspect = schema_invoke('inspect');
  }
  if (empty($inspect[$table])) {
    if ($die_on_empty) {
      drush_die(t('Table !table does not exist in database.', array('!table' => $table)));
    }
    else {
      return FALSE;
    }
  }
  else {
    return $inspect[$table];
  }
}

/**
 * Returns the first varchar type field as alias. If none is found, return primary key.
 *
 * @param array $schema
 * @return string
 */
function autoadmin_drush_default_schema_alias($schema) {
  foreach($schema['fields'] as $key => $field) {
    if(!empty($field['type']) && $field['type'] == 'varchar') {
      return $key;
    }
  }
  return $schema['primary key'][0];
}