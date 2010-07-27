<?php
// $Id: autoadmin.drush.helper.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Various helper functions. Notice these functions are not namespaced.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Output and/or write to file.
 * 
 * @param string $module
 * @param string $file_name
 * @param string $file_code
 */
function autoadmin_drush_output_file($module, $file_name, $content) {
  if (!drush_get_option(array('no-output', 'n'))) {
    drush_print($content);
  }
  if (drush_get_option(array('write', 'w'))) {
    autoadmin_drush_write_file($module, $file_name, $content);
  }
}

/**
 * Preg replace callback function for autoadmin_drush_theme_array.
 *
 * @param array $matches
 * @return string
 */
function autoadmin_drush_clean_array_cb($matches) {
  return "array('$matches[1]')";
}

/**
 * Preg replace callback function for autoadmin_drush_theme_array.
 *
 * @param array $matches
 * @return string
 */
function autoadmin_drush_add_t_cb($matches) {
  return "'$matches[1]' => t('$matches[2]'),";
}

/**
 * Insert two spaces in the beginning of all lines except the first one.
 * 
 * @param string $text
 * @return string
 */
function autoadmin_drush_insert_tab($text) {
  return str_replace("\n", "\n  ", $text);
}

/**
 * Returns the tables in schemas comma seperated.
 * 
 * @param array $schemas
 * @return array
 */
function autoadmin_drush_theme_tables($tables, $extract_keys = TRUE) {
  if ($extract_keys) {
    $tables = array_keys($tables);
  }
  foreach ($tables as &$table) {
    $table = "{{$table}}";
  }
  return implode(', ', $tables);
}

/**
 * Check if modules exists. If not die.
 * 
 * @param array/string $modules
 *   Accepts a string for a single module.
 */
function autoadmin_drush_check_modules_exists($modules) {
  $modules = (array)$modules;
  foreach ($modules as $module) {
    if (!module_exists($module)) {
        drush_die(t('Module !module does not exist.', array('!module' => $module)));
    }
  }
}

/**
 * Confirms overwriting a file.
 * 
 * @param string $file
 * @return bool
 */
function autoadmin_confirm_overwrite($file) {
  return autoadmin_drush_confirm(
    t('The file !file already exists. Are you sure you want to overwrite it?',
    array('!file' => $file)
    ));
}

/**
 * Write content to a file. If the file does exist, confirm overwriting.
 * 
 * @param string $module
 * @param string $filename
 * @param string $content
 */
function autoadmin_drush_write_file($module, $filename, $content) {
  if (empty($content)) {
    drush_die(t('Cant write empty content to file !file', array('!file' => $file)));
  }
  $file = AUTOADMIN_MODULESPATH . "/$module/$module.$filename";
  if (file_exists($file)) {
    if (autoadmin_drush_file_is_writeable($file)) {
      if (autoadmin_confirm_overwrite($file)) {
        file_put_contents($file, $content);
        drush_print(t('Wrote content to file !file', array('!file' => $file)));
      }
    }
  } else {
    if (autoadmin_drush_file_is_writeable(dirname($file))) {
      file_put_contents($file, $content);
      drush_print(t('Wrote content to file !file', array('!file' => $file)));
    }
  }
}

/**
 * Load content. Die if file not exists.
 *
 * @param <type> $file
 * @return <type>
 */
function autoadmin_drush_load_file($file) {
  if (!file_exists($file)) {
    drush_die(t('File !file does not exist.', array('!file' => $file)));
  }
  return file_get_contents($file);
}

/**
 * Die if file is not writable.
 * 
 * @param string $file
 * @return TRUE
 */
function autoadmin_drush_file_is_writeable($file) {
  if (!is_writable($file)) {
    drush_die(t('The file !file is not writeable', array('!file' => $file)));
  }
  else {
    return TRUE;
  }
}

/**
 * Returns all schemas for a module
 * 
 * @param string $module
 * @return array
 */
function autoadmin_drush_get_schemas($module) {
  $schemas = drupal_get_schema_unprocessed($module);
  if (!is_array($schemas)) {
    drush_die(t('No schemas defined for module !module.', array('!module' => $module)));
  }
  return $schemas;
}

/**
 * Removes descriptions fields from php code with arrays inside it.
 * 
 * @param string $string
 * @return string
 */
function autoadmin_drush_remove_description($string) {
  return str_replace("'description' => t('TODO: please describe this field!'),\n      ", '', $string);
}

/**
 * Checks that arguments complies with first a module and then tables.
 * 
 * @param array $args
 * @param string $module
 *   By ref.
 * @param array $tables
 *   By ref.
 * @param bool $tables_can_be_empty
 */
function autoadmin_drush_args_module_then_tables($args, &$module, &$tables, $tables_can_be_empty = FALSE) {
  $tables = $args;
  $module = array_shift($tables);
  autoadmin_drush_check_modules_exists($module);
  if (!$tables_can_be_empty && empty($tables)) {
    drush_die(t('At least one table must be stated in argument.'));
  }
}

/**
 * Sort the schema so it looks nice when the code is output.
 * 
 * @param array $schema
 * @return array
 */
function autoadmin_drush_sort_schema($schema) {
  $sorted_schema = array();
  ksort($schema);
  foreach ($schema['fields'] as &$field) {
    ksort($field);
  }
  $sorted_schema = $schema;
  foreach (array('fields', 'relations', 'primary key') as $key) {
    if (isset($sorted_schema[$key])) {
      unset($sorted_schema[$key]);
      $sorted_schema[$key] = $schema[$key];
    }
  }
  return $sorted_schema;
}

/**
 * Ask the user a basic yes/no question. This is a variant of the drush_confirm() function.
 * The problem with the drush_confirm() function was that it did not work when calling
 * other drush commands with the system() function.
 *
 * @param $msg
 *   The question to ask.
 * @return bool
 *   TRUE If the user entered 'y', FALSE if he entered 'n'.
 */
function autoadmin_drush_confirm($msg, $indent = 0) {
  print str_repeat(' ', $indent) . (string)$msg . " (y/n):\n";
  if (drush_get_context('DRUSH_AFFIRMATIVE')) {
    print "y\n";
    return TRUE;
  }
  // See http://drupal.org/node/499758 before changing this.
  $stdin = fopen("php://stdin","r");
  while ($line = fgets($stdin)) {
  $line = trim($line);
    if ($line == 'y') {
      return TRUE;
    }
    if ($line == 'n') {
      return FALSE;
    }
    print str_repeat(' ', $indent) . (string)$msg . " (y/n):\n";
  }
}