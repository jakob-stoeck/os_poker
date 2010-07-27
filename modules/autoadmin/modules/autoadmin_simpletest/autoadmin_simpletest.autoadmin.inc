<?php
// $Id

/**
 * @file
 * Auto Admin schemas for autoadmin_simpletest module.
 *
 * @tables {autoadmin_simpletest}, {autoadmin_simpletest_has_many}, {autoadmin_simpletest_has_one}
 */

/**
 * Implementation of hook_autoadmin_schema().
 */
function autoadmin_simpletest_autoadmin_schema() {
  // Auto Admin schema for {autoadmin_simpletest} table
  $autoadmin_schema['autoadmin_simpletest'] = array(
    'alias' => 'test_varchar',
    'inline_filter' => FALSE,
    'path' => 'admin/content/autoadmin_simpletest',
    'title' => t('Autoadmin Simpletest'),
    'title_plural' => t('Autoadmin Simpletests'),
    'fields' => array(
      'id' => array(
        'autoadmin_type' => 'primary_key',
        'title' => t('Id'),
        'title_plural' => t('Ids'),
      ),
      'test_bool' => array(
        'autoadmin_type' => 'bool',
        'title' => t('Test Bool'),
        'title_plural' => t('Test Bools'),
      ),
      'test_int' => array(
        'autoadmin_type' => 'int',
        'title' => t('Test Int'),
        'title_plural' => t('Test Ints'),
      ),
      'test_varchar' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Test Varchar'),
        'title_plural' => t('Test Varchars'),
      ),
      'test_text' => array(
        'autoadmin_type' => 'text',
        'title' => t('Test Text'),
        'title_plural' => t('Test Texts'),
      ),
    ),
    'relations' => array(
      'autoadmin_simpletest_has_many' => array(
        'type' => 'has_many',
        'local_key' => 'id',
        'foreign_key' => 'autoadmin_simpletest_id',
      ),
    ),
  );

  // Auto Admin schema for {autoadmin_simpletest_has_many} table
  $autoadmin_schema['autoadmin_simpletest_has_many'] = array(
    'alias' => 'test_varchar',
    'inline_filter' => FALSE,
    'path' => 'admin/content/autoadmin_simpletest_has_many',
    'title' => t('Autoadmin Simpletest Has Many'),
    'title_plural' => t('Autoadmin Simpletest Has Manies'),
    'fields' => array(
      'id' => array(
        'autoadmin_type' => 'primary_key',
        'title' => t('Id'),
        'title_plural' => t('Ids'),
      ),
      'test_varchar' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Test Varchar'),
        'title_plural' => t('Test Varchars'),
      ),
      'test_bool_override_options' => array(
        'autoadmin_type' => 'bool',
        'title' => t('Test Bool Override Option'),
        'title_plural' => t('Test Bool Override Options'),
        '#form' => array(
          '#type' => 'select',
          '#options' => array('0' => t('Option for no'), '1' => t('Option for yes'))
        ),
      ),
      'autoadmin_simpletest_id' => array(
        'autoadmin_type' => 'has_one',
        'title' => t('Autoadmin Simpletest'),
        'title_plural' => t('Autoadmin Simpletests'),
      ),
    ),
    'relations' => array(
      'autoadmin_simpletest' => array(
        'type' => 'has_one',
        'local_key' => 'autoadmin_simpletest_id',
        'foreign_key' => 'id',
      ),
    ),
  );

  return $autoadmin_schema;
}