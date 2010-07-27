<?php
// $Id: autoadmin.delete.inc,v 1.2 2009/12/06 21:11:00 rune Exp $

/**
 * @file
 * Handles the delete page.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Build the delete confirmation form.
 *
 * @param array $form_state
 * @param array $schema
 * @param int $primary_value
 * @return array the return form
 */
function autoadmin_delete_confirm($form_state, $table, $primary_value) {
  $schema = autoadmin_init_page($table);
  $query = autoadmin_query_get_query($schema, $primary_value);
  $row = db_fetch_array(db_query($query['sql'], $query['placeholders']));
  $form['schema'] = array(
    '#type' => 'value',
    '#value' => $schema,
  );
  $form['primary_value'] = array(
    '#type' => 'value',
    '#value' => $primary_value,
  );
  _autoadmin_delete_confirm_cascading($form, $schema, $primary_value);
  return confirm_form(
    $form,
    t('Are you sure you want to delete the %alias: %title?', array('%alias' => $schema['title'], '%title' => $row[$schema['alias']])),
    isset($_GET['destination']) ? $_GET['destination'] : $schema['path'] . '/list',
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Add display of what is going to be cascadely deleted.
 *
 * @param array $form
 * @param array $schema
 * @param int $primary_value
 */
function _autoadmin_delete_confirm_cascading(&$form, $schema, $primary_value) {
  if (empty($schema['relations'])) {
    return NULL;
  }
  $row = autoadmin_db_fetch_row($schema, $primary_value);
  $form['deletes'] = array(
    '#type' => 'value',
    '#value' => array()
  );
  foreach ($schema['relations'] as $relation) {
    if ($relation['cascading_delete']) {
      $rows = autoadmin_db_fetch_foreign_deletes($relation, $row);
      if (!empty($rows)) {
        // Add html to form showing rows pending deletion.
        _autoadmin_delete_get_cascading_delete_form_item($form, $schema, $relation['local_key'], $relation, $rows);
        $form['deletes']['#value'][] = array(
          'table' => $relation['foreign_table'],
          'key' => $relation['foreign_key'],
          'value' => $row[$relation['local_key']],
        );
      }
    }
  }
}

/**
 * Deletes rows.
 *
 * @param array $form
 * @param array $form_state
 */
function autoadmin_delete_confirm_submit($form, &$form_state) {
  if ($form_state['values']['confirm']) {
    if (autoadmin_db_delete_row($form_state['values']['schema'], $form_state['values']['primary_value'])) {
      drupal_set_message(t('Your %title was deleted.', array('%title' => $form_state['values']['schema']['title'])));
      _autoadmin_delete_delete_cascading($form_state['values'], $form_state['values']['schema']);
    }
    else {
      drupal_set_message(t('Your %title could not be deleted. Maybe someone deleted it before you did.', array('%title' => $form_state['values']['schema']['title'])));
    }
  }
  drupal_goto($form_state['values']['schema']['path'] . '/list');
}

/**
 * Delete cascading_deletes if any.
 *
 * @param array $values
 */
function _autoadmin_delete_delete_cascading($values, $schema) {
  if (!empty($values['deletes'])) {
    foreach ($values['deletes'] as $delete) {
      autoadmin_db_delete_where($delete['table'], $delete['key'], $delete['value']);
      $schema = autoadmin_schema_get_one($delete['table']);
      $count = db_affected_rows();
      $title = $count < 2 ? $schema['title'] : $schema['title_plural'];
      drupal_set_message(t('Deleted %count %type.', array('%count' => $count, '%type' => $title)));
    }
    autoadmin_session_clear_schema($schema);
  }
}

/**
 * Generates the form item showing what will be deleted cascadingly.
 *
 * @param array $form
 * @param array $schema
 * @param string $field_key
 * @param array $relation
 * @param array $rows
 */
function _autoadmin_delete_get_cascading_delete_form_item(&$form, $schema, $field_key, $relation, $rows) {
  $foreign_schema = autoadmin_schema_get_one($relation['foreign_table']);
  $count = count($rows);
  $title = $count < 2 ? $foreign_schema['title'] : $foreign_schema['title_plural'];
  $form['delete' . $field_key] = array(
      '#value' => t('This %title has %count %foreign_name. The following %foreign_name will also be deleted! ', array(
      '%title' => $schema['title'],
      '%count' => $count,
      '%foreign_name' => $title,
    ))
    . theme('autoadmin_cascading_deletes', $rows, $relation)
  );
}