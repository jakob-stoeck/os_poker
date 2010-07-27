<?php
// $Id: autoadmin.db.inc,v 1.2 2009/12/06 21:11:00 rune Exp $

/**
 * @file
 * Database functions.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

 /**
 * Fetch single row based on schema and primary value.
 *
 * @param array $schema
 * @param array $primary_value
 * @return array/FALSE
 */
function autoadmin_db_fetch_row($schema, $primary_value) {
	db_set_active('pythonpokernetwork');
  if (!ctype_digit((string)$primary_value)) {
    return FALSE;
  }
  $return = db_fetch_array(db_query(
      "SELECT * FROM {{$schema['table']}} WHERE %s=%d",
      $schema['primary key'][0],
      $primary_value
  ));
	db_set_active('default');
	return $return;
}

/**
 * Returns keyed array of options for a field with a has_one relation.
 *
 * @param array $field
 *  Field with a has_one relation.
 * @return array
 */
function autoadmin_db_fetch_options($schema, $field) {
	db_set_active('pythonpokernetwork');
  $relation = autoadmin_get_has_one_relation($schema, $field);
  $foreign_schema = autoadmin_schema_get_one($relation['foreign_table']);
  $pk = $foreign_schema['primary key'][0];
  $res = db_query("SELECT $pk, $relation[foreign_alias] FROM {{$relation['foreign_table']}}");
  $rows = (isset($field['not NULL']) && $field['not NULL']) ? array() : array(0 => '----');
  while ($row = db_fetch_array($res)) {
    $value = $row[$pk];
    $rows[$value] = $row[$relation['foreign_alias']];
  }
	db_set_active('default');
  return $rows;
}

/**
 * Deletes a row based on schema and primary value.
 *
 * @param array $schema
 * @param int $primary_value
 * @return FALSE/int
 *   Numbers of affected rows or FALSE.
 */
function autoadmin_db_delete_row($schema, $primary_value) {
	db_set_active('pythonpokernetwork');
  $return = db_query("DELETE FROM {{$schema['table']}} WHERE %s='%d'", $schema['primary key'][0], $primary_value);
	db_set_active('default');
	return $return;
}

/**
 * Deletes from table where $field_key equals $value.
 * 
 * @param string $table
 * @param string $field_key
 * @param int $value
 * @return object
 */
function autoadmin_db_delete_where($table, $field_key, $value) {
	db_set_active('pythonpokernetwork');
  $return = db_query("DELETE FROM {{$table}} WHERE %s='%d'", $field_key, $value);
	db_set_active('default');
	return $return;
}

/**
 * Get rows that will be deleted from foreign schema.
 * 
 * @param string $relation
 * @param string $field_key
 * @param array $row
 * @return array
 */
function autoadmin_db_fetch_foreign_deletes($relation, $row) {
  $foreign_rows = array();
  $result = db_query("SELECT * FROM {{$relation['foreign_table']}} WHERE %s=%d",
      $relation['foreign_key'],
      $row[$relation['local_key']]
  );
  while ($foreign_row = db_fetch_array($result)) {
    $foreign_rows[] = $foreign_row;
  }
  return $foreign_rows;
}