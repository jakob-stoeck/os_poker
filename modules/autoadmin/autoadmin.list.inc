<?php
// $Id: autoadmin.list.inc,v 1.2 2009/12/06 21:11:00 rune Exp $

/**
 * @file
 * Handles the list page.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

// Constants.
define('AUTOADMIN_LIST_LIMIT', 20);
define('AUTOADMIN_LIST_TRUNCATE', 60);
/**
 * Generates list view and returns themed html.
 *
 * @param array $schema
 *   Current schema.
 * @return string
 *   Html for output.
 */
function autoadmin_list($table) {
  $schema = autoadmin_init_page($table);
  $query = autoadmin_query_get_list_query($schema);
  $header = _autoadmin_list_header($schema);
  $query['sql'] .= tablesort_sql($header);
	db_set_active('pythonpokernetwork');
  $result = pager_query($query['sql'], AUTOADMIN_LIST_LIMIT, 0, NULL, $query['placeholders']);
	db_set_active('default');
  $rows = _autoadmin_list_rows($result, $schema);
  return
    autoadmin_get_page_description($schema)
    . theme('table', $header, $rows)
    . theme('pager', NULL, AUTOADMIN_LIST_LIMIT)
  ;
}

/**
 * Returns header array for list view.
 *
 * @param array $schema
 * @return array
 */
function _autoadmin_list_header($schema) {
  $header = array();
  foreach ($schema['fields'] as $field_key => $field) {
    $header[] = array('data' => $field['title'], 'field' => $field_key);
  }
  $header[] = t('Operations');
  return $header;
}

/**
 * Fetches rows from page query result.
 *
 * @param DbObject $result
 * @param array $schema
 * @return array
 */
function _autoadmin_list_rows($result, $schema) {
  $rows = array();
  while ($db_row = db_fetch_array($result)) {
    $rows[] = _autoadmin_list_row($db_row, $schema);
  }
  if (!$rows) {
    return _autoadmin_list_no_rows($schema);
  }
  _autoadmin_list_add_inline_filter_row($schema, $rows);
  return $rows;
}

/**
 * Returns a row with text displaying that no rows has been added.
 *
 * @param array $schema
 * @return array
 */
function _autoadmin_list_no_rows($schema) {
  $rows = array();
  // Fill td's to fit header th's.
  $rows[0] = array_fill(0, count($schema['fields']), '');
  array_unshift($rows[0], t('No records have been added.'));
  return $rows;
}

/**
 * Generate td's for a row.
 *
 * @param array $db_row a row from db query
 * @param array $schema
 * @return array a row to put in the list table
 */
function _autoadmin_list_row($db_row, $schema) {
  $table_row = array();
  foreach ($schema['fields'] as $field_key => $field) {
		if ($field['autoadmin_type'] != 'bool') {
			 $table_row[$field_key] = check_plain(truncate_utf8($db_row[$field_key], AUTOADMIN_LIST_TRUNCATE, FALSE, TRUE));
		} else {
			$table_row[$field_key] = _autoadmin_list_field_bool($db_row, $field_key, $schema);
		}
  }
  // Add operation links.
  $table_row[] =
      _autoadmin_list_operations($db_row, $table_row, $schema)
    . _autoadmin_list_operations_has_many($schema, autoadmin_get_pv($db_row, $schema));
  return $table_row;
}

/**
 * This function is still a stub. The inline filter functionality is not yet implemented.
 * Adds the row for the inline filter.
 *
 * @todo Finish this feature. And make it look nice.
 * @param array $schema
 * @param array $rows
 *   By ref.
 * @return array
 */
function _autoadmin_list_add_inline_filter_row($schema, &$rows) {
  if (!$schema['inline_filter']) {
    return NULL;
  }
  module_load_include('inc', 'autoadmin', 'autoadmin.edit');
  $row = array();
  foreach ($schema['fields'] as $field_key => $field) {
    $type = autoadmin_get_callback_function('', $schema, $field);
    $filter_name = "filter_$field[key]";
    switch ($type) {
      case 'bool':
        $element = _autoadmin_edit_build_field($schema, $field, $row, &$weight);
        $element = array_merge($element,array(
          '#description' => '',
          '#multiple' => FALSE,
          '#size' => NULL,
          '#required' => FALSE,
          '#parents' => array(NULL),
          '#name' => '',
          '#id' => '',
          '#value' => '',
          '#title' => '',
          '#return_value' => 1
        ));
        $row[$field_key] = theme('checkbox', $element);
        break;
      case 'has_one':
        $options = autoadmin_db_fetch_options($schema, $field);
        $row[$field_key] = theme('select', array(
          '#type' => 'select',
          '#title' => '',
          '#default_value' => !empty($_GET[$filter_name]) ? $_GET[$filter_name] : '',
          '#options' => $options,
          '#description' => '',
          '#multiple' => FALSE,
          '#size' => NULL,
          '#required' => FALSE,
          '#parents' => array(NULL),
          '#name' => '',
          '#id' => '',
          '#value' => '',
        ));
        break;
      default:
        $value = !empty($_GET[$filter_name]) ? $_GET[$filter_name] : '';
        $row[$field_key] = "<input value='$value' name='$filter_name'/>";
    }
  }
  // Add form to first field
  $first_field = current($schema['fields']);
  $row[$first_field['key']] = '<form id="autoadmin-form-filter">' . $row[$first_field['key']];
  // Add operation links.
  $row[] = '<input id="autoadmin_filter_submit" class="form-submit" type="submit" value=" ' . t('Filter'). '"/></form>';
  array_unshift($rows, $row);
}

/**
 * Create edit and delete links.
 *
 * @param array $table_row
 * @param array $schema
 * @return string
 */
function _autoadmin_list_operations($db_row, $table_row, $schema) {
  $primary_value = $table_row[$schema['primary key'][0]];
  $operations = array(
    'edit' => array(
      'title' => t('Edit'),
      'href' => $schema['path'] . '/' . $primary_value . '/edit',
    ),
    'delete' => array(
      'title' => t('Delete'),
      'href' => $schema['path'] . '/' . $primary_value . '/delete',
    ),
  );
  return theme('autoadmin_operations', $operations);
}

/**
 * Create links to subschemas where current schema has one_to_many relations.
 *
 * @param array $schema
 * @param int $primary_value
 * @return string html
 */
function _autoadmin_list_operations_has_many($schema, $primary_value) {
  if (empty($schema['relations_by_type']['has_many'])) {
    return NULL;
  }
  foreach ($schema['relations_by_type']['has_many'] as $relation) {
    $foreign_schema = autoadmin_schema_get_one($relation['foreign_table']);
    $links[] = array(
      'title' => t('Edit !title_plural', array('!title_plural' => $foreign_schema['title_plural'])),
      'href' => $foreign_schema['path'],
      'query' => $relation['foreign_key'] . '=' . $primary_value,
    );
  }
  return theme('autoadmin_operations', $links);
}

/**
 * Creates list value for bool fields.
 * 
 * @param array $db_row
 * @param string $field_key
 * @param array $schema
 * @return string
 */
function _autoadmin_list_field_bool($db_row, $field_key, $schema) {
  $value = $db_row[$field_key];
  if (!empty($schema['fields'][$field_key]['#form']['#options'][$value])) {
    return $schema['fields'][$field_key]['#form']['#options'][$value];
  }
  else {
    $choices = array(t('No'), t('Yes'));
    return $choices[$value];
  }
}