<?php
/**
 * @file
 * Validation functions.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 * @todo This file looks messy.
 */

// Globals.
define('AUTOADMIN_VALIDATE_NOT_SET', -94897565123);

/**
 * Schema Validation
 */

/**
 * Validates schema and outputs errors messages.
 *
 * @todo This function could be simpler.
 * @staticvar bool $schema_is_valid
 * @param array $schema
 * @param string $module
 * @param string $table
 * @param array $structure
 * @param string $base_path
 * @return bool
 */
function autoadmin_validate_schema($schema, $module, $table, $structure=NULL, $base_path='') {
  static $schema_is_valid;
  if (!$structure) {
    $structure = autoadmin_validate_schema_array_structure();
    $schema_is_valid = TRUE;
  }
  foreach ($structure as $key => $sub_structure) {
    if (empty($key)) {
      autoadmin_validate_set_error_message('Keys cannot be empty.', $module, $table, $base_path);
    }
    $path = $base_path . "[$key]";
    if (!empty($schema[$key])) {
      $sub_schema = $schema[$key];
    } else {
      $sub_schema = AUTOADMIN_VALIDATE_NOT_SET;
    }
    if (!empty($sub_structure)) {
      if (autoadmin_validate_structure($sub_structure, $sub_schema, $module, $table, $path, $key) !== TRUE) {
        $schema_is_valid = FALSE;
      }
    }
    if ($sub_schema != AUTOADMIN_VALIDATE_NOT_SET && !empty($sub_structure['validate_children'])) {
      $validate_children = $sub_structure['validate_children'];
      foreach($sub_schema as $key => $sub_sub_schema) {
        $sub_path = $path . "[$key]";
        autoadmin_validate_schema($sub_sub_schema, $module, $table, $validate_children, $sub_path);
      }
    }
  }
  return $schema_is_valid;
}

/**
 * Validate that the $data complies with the validate structure settings.
 * 
 * @param array $validate
 * @param mixed $data
 * @param string $module
 * @param string $table
 * @param string $path
 * @return bool
 */
function autoadmin_validate_structure($validate, $data, $module, $table, $path, $key) {
  if ($validate['required'] == TRUE) {
    if ($data == AUTOADMIN_VALIDATE_NOT_SET) {
      autoadmin_validate_set_error_message('The setting !path is required.', $module, $table, $path);
      return FALSE;
    }
  }
  if ($data == AUTOADMIN_VALIDATE_NOT_SET) {
    return TRUE;
  }
  if (gettype($data) != $validate['type']) {
    autoadmin_validate_set_error_message(t('Type of !path must be of type !type.',
      array('!type' => $validate['type'])), $module, $table, $path);
    return FALSE;
  }
  if (!empty($validate['allowed_values']) && !in_array($data, $validate['allowed_values']) ) {
    autoadmin_validate_set_error_message(t('Value of !path must be one of: (!values).', 
      array('!type' => $validate['type'], '!values' => join(',', $validate['allowed_values'])))
      , $module, $table, $path);
    return FALSE;
  }
  if (!empty($validate['function'])) {
    return $validate['function']($validate, $data, $module, $table, $path, $key);
  }
  return TRUE;
}

/**
 * Adds a error message.
 * 
 * @param string $msg
 * @param string $module
 * @param string $table
 * @param string $path
 */
function autoadmin_validate_set_error_message($msg, $module, $table, $path) {
  drupal_set_message(t('Module=!module,Table=!table: ' . $msg,
    array('!module' => $module, '!table' => $table, '!path' => $path)), 'error', FALSE);
}


/**
 * Validates that given alias exists in the database.
 *
 * @param array $validate
 * @param mixed $data
 * @param string $module
 * @param string $table
 * @param string $path
 * @param string $key
 * @return bool
 */
function validate_alias_exists($validate, $data, $module, $table, $path, $key) {
  $inspected_schema = autoadmin_get_inspected_schema($table, FALSE);
  if (empty($inspected_schema['fields'][$data])) {
    autoadmin_validate_set_error_message(
      t('Field !field does not exist in database.'
      , array('!field' => $data)), $module, $table, $path);
    return FALSE;
  }
  return TRUE;
}

/**
 * Validates that given fields exists in the database.
 * 
 * @param array $validate
 * @param mixed $data
 * @param string $module
 * @param string $table
 * @param string $path
 * @param string $key
 * @return bool
 */
function validate_fields_exists($validate, $data, $module, $table, $path, $key) {
  if (empty($data)) {
    return FALSE;
  }
  $fields_exists = TRUE;
  $inspected_schema = autoadmin_get_inspected_schema($table, FALSE);
  foreach ($data as $key => $field) {
    if (empty($inspected_schema['fields'][$key])) {
      autoadmin_validate_set_error_message(
        t('Field !field does not exist in database.', array('!field' => $key))
        , $module, $table, $path);
      $fields_exists = FALSE;
    }
  }
  return $fields_exists;
}

/**
 * Validates that given relations exists in the database.
 *
 * @param array $validate
 * @param mixed $data
 * @param string $module
 * @param string $table
 * @param string $path
 * @param string $key
 * @return bool
 */
function validate_relations($validate, $data, $module, $table, $path, $key) {
  if (empty($data)) {
    return FALSE;
  }
  $local_inspected_schema = autoadmin_get_inspected_schema($table, FALSE);
  $relations_are_valid = TRUE;
  foreach ($data as $key => $field) {
    $foreign_inspected_schema = autoadmin_get_inspected_schema($key, FALSE);
    if (!$foreign_inspected_schema) {
      autoadmin_validate_set_error_message(t('Table !table does not exist in database.', array('!table' => $key)), $module, $table, $path);
      $relations_are_valid = FALSE;
    }
    else {
      if (empty($foreign_inspected_schema['fields'][$field['foreign_key']])) {
        autoadmin_validate_set_error_message(t('Foreign key !foreign_key does not exist in database.', array('!foreign_key' => $field['foreign_key'])), $module, $table, $path);
      }
      if (empty($local_inspected_schema['fields'][$field['local_key']])) {
        autoadmin_validate_set_error_message(t('Field !field does not exist in database.', array('!field' => $key)), $module, $table, $path);
        $fields_exists = FALSE;
      }
    }
  }
  return $relations_are_valid;
}


/**
 * Validation data for schemas.
 * 
 * @return array
 */
function autoadmin_validate_schema_array_structure() {
  return array(
    'alias' => array(
      'required' => TRUE,
      'type' => 'string',
      'function' => 'validate_alias_exists',
     ),
    'path' => array(
      'required' => TRUE,
      'type' => 'string',
     ),
    'title' => array(
      'required' => TRUE,
      'type' => 'string',
     ),
     'title_plural' => array(
      'required' => TRUE,
      'type' => 'string',
     ),
    'fields' => array(
        'required' => TRUE,
        'type' => 'array',
        'function' => 'validate_fields_exists',
        'validate_children' => array(
          'autoadmin_type' => array(
            'required' => TRUE,
            'type' => 'string',
            'allowed_values' => array('int', 'varchar', 'text', 'serial', 'has_one', 'has_many', 'primary_key', 'weight', 'bool'),
          ),
          'title' => array(
            'required' => TRUE,
            'type' => 'string',
            
          ),
          'title_plural' => array(
            'required' => TRUE,
            'type' => 'string',
          ),
        ),
    ),
    'relations' => array(
      'required' => FALSE,
      'type' => 'array',
      'function' => 'validate_relations',
      'validate_children' => array(
        'type' => array(
          'required' => TRUE,
          'type' => 'string',
          'allowed_values' => array('has_many', 'has_one'),
        ),
        'local_key' => array(
          'required' => TRUE,
          'type' => 'string',
        ),
        'foreign_key' => array(
          'required' => TRUE,
          'type' => 'string',
        ),
      ),
    ),
  );
}

/* end schema validation */

/**
 * form field validation
 */

function autoadmin_validate_int($element, &$form_state) {
   if (!is_numeric($element['#value']) || (int)$element['#value'] != $element['#value']) {
     form_error($element, t('<strong>!field:</strong> Must be a positive or negative whole number.', array('!field' => $element['#title'])));
   }
}
/* end form field validation */