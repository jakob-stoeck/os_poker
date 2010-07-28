<?php
// $Id: autoadmin.query.inc,v 1.2 2009/12/06 21:11:00 rune Exp $

/**
 * @file
 * Generates queries.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

 /**
 * Returns query for the list view.
 * 
 * @param array $schema
 * @return array
 */
function autoadmin_query_get_list_query($schema) {
  autoadmin_query_init_query($query);
  autoadmin_query_set_from($query, $schema);
  foreach ($schema['fields'] as $field_key => $field) {
    if (autoadmin_field_has_one_relation($schema, $field)) {
      autoadmin_query_add_join($query, $schema, $field);
      _autoadmin_query_filter_has_one($query, $schema, $field);
    } else {
      autoadmin_query_add_select($query, $field);
    }
  }
  $query = autoadmin_query_assemble_query($query);
  return $query;
}

/**
 * Returns query for edit and delete view.
 * 
 * @param array $schema
 * @param int/NULL $primary_value
 * @return array
 */
function autoadmin_query_get_query($schema, $primary_value) {
  autoadmin_query_init_query($query);
  autoadmin_query_set_from($query, $schema);
  autoadmin_query_add_where(
    $query,
    autoadmin_schema_get_primary_field($schema),
    $primary_value
  );
  foreach ($schema['fields'] as $field_key => $field) {
    autoadmin_query_add_select($query, $field);
  }
  $query = autoadmin_query_assemble_query($query);
  return $query;
}

/**
 * Initialize the query array.
 *
 * @param array $query
 */
function autoadmin_query_init_query(&$query) {
  $query = array(
    'sql' => '',
    'select' => array(),
    'join' => array(),
    'where' => array(),
    'placeholders' => array(),
  );
}

/**
 * Store from to query.
 * 
 * @param array $query
 * @param array $schema
 */
function autoadmin_query_set_from(&$query, $schema) {
  $query['from'] = $schema['table'];
}

/**
 * Add select statement to query for field.
 *
 * @param array $query
 * @param array $field
 */
function autoadmin_query_add_select(&$query, $field) {
    $query['select'][] = "t.$field[key]";
}

/**
 * Add join statement for field. Used for fields with has_one relation.
 * 
 * @param array $query
 * @param array $schema
 * @param array $field
 */
function autoadmin_query_add_join(&$query, $schema, $field) {
  $relation = autoadmin_get_has_one_relation($schema, $field);
  $query['select'][] = "{{$relation['foreign_table']}}.$relation[foreign_alias] as $field[key]";
  $query['join']["LEFT JOIN {{$relation['foreign_table']}} ON {%s}.%s=t.%s"][] = array(
    $relation['foreign_table'],
    $relation['foreign_key'],
    $field['key'],
  );
}

/**
 * Add where statement.
 *
 * @param array $query
 * @param array $field
 * @param array $value
 */
function autoadmin_query_add_where(&$query, $field, $value) {
  $placeholder = db_placeholders(array($value), $field['type']);
  $sql = '%s=' . $placeholder;
  $query['where'][$sql][] = array("t.$field[key]", $value);
}

/**
 * Assemble the different statements of the query.
 * 
 * @param array $query
 * @return array
 */
function autoadmin_query_assemble_query(&$query) {
  $query['sql'] = 'SELECT ';
  autoadmin_query_assemble_part($query, 'select', ',');
  $query['sql'] .= ' FROM {' . $query['from'] . '} t ';
  if (!empty($query['join'])) {
    autoadmin_query_assemble_part($query, 'join', " ");
  }
  if (!empty($query['where'])) {
    $query['sql'] .= ' WHERE ';
    autoadmin_query_assemble_part($query, 'where', " AND ");
  }
  autoadmin_query_debug($query);
  return $query;
}

/**
 * Assembles par of query.
 * 
 * @param array $query
 * @param string $part
 * @param string $seperator
 */
function autoadmin_query_assemble_part(&$query, $part, $seperator) {
  foreach ($query[$part] as $key => $value) {
    if (!is_array($value)) {
      $query['sql'] .= $value . $seperator;
    }
    else {
      foreach ($value as $placeholders) {
        $query['sql'] .= $key . $seperator;
        $query['placeholders'] = array_merge($query['placeholders'], $placeholders);
      }
    }
  }
  $query['sql'] = substr($query['sql'], 0, -strlen($seperator));
}

/**
 * Handles filters for has_one fields.
 *
 * @param array $schema
 * @param array $field
 * @return NULL
 *	If no filters apply for field
 */
function _autoadmin_query_filter_has_one(&$query, $schema, $field) {
  $value = autoadmin_get_filter_value($schema, $field);
  if (!isset($value)) {
    return NULL;
  }
  autoadmin_session_clear_schema($schema);
  autoadmin_session_set_field_param($schema, $field, $value);
  autoadmin_set_filter_page_description($schema, $field, $value);
  autoadmin_query_add_where($query, $field, $value);
}

/**
 * Outputs the query if ?AUTOADMIN_DEBUG is present in the url.
 *
 * @uses Devel module http://drupal.org/project/devel
 * @param array $query
 * @return NULL;
 */
function autoadmin_query_debug($query) {
  if (!isset($_GET['AUTOADMIN_DEBUG'])) {
    return NULL;
  }
  dpr($query['sql'], $query['placeholders']);
  _db_query_callback($query['placeholders'], TRUE);
  $query['sql'] = preg_replace_callback(DB_QUERY_REGEXP, '_db_query_callback', $query['sql']);
  $query['sql'] = db_prefix_tables($query['sql']);
  dpr($query);
  return NULL;
}