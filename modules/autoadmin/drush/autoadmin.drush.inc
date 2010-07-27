<?php
// $Id: autoadmin.drush.inc,v 1.1 2009/12/06 21:13:00 rune Exp $

/**
 * @file
 * Drush Commands for Auto Admin.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 * @todo There is code duplication with the update functions. This could be generalized.
 */

// Globals.
define('AUTOADMIN_MODULESPATH', realpath(dirname(__FILE__) . '/../..'));
define('AUTOADMIN_FILEPATH', AUTOADMIN_MODULESPATH . '/autoadmin');
define('AUTOADMIN_DRUSH_FILEPATH', AUTOADMIN_FILEPATH . '/drush');

// Includes.
require AUTOADMIN_FILEPATH . '/autoadmin.text.inc';
require AUTOADMIN_FILEPATH . '/autoadmin.helper.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.auto.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.helper.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.generate.schema.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.update.schema.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.generate.install.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.update.install.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.generate.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.update.inc';
require AUTOADMIN_DRUSH_FILEPATH . '/autoadmin.drush.theme.inc';

/**
 * Implementation of hook_drush_command().
 *
 * @See drush_parse_command() for a list of recognized keys.
 * @return array
 *   An associative array describing your command(s).
 */
function autoadmin_drush_command() {
  $items = array();
  $items['autoadmin'] = array();
  $items['autoadmin-generate'] = array(
    'callback' => 'autoadmin_drush_generate',
    'description' => dt('Generates both .install file and Auto Admin schema.'),
    'arguments' => array('[module] [table1] [table2] ... [tableN]' => dt('First the module, and then all tables to be generated')),
    'options' => array(
      '-w, --write' => dt('Write to [module]/[module].autoadmin.inc'),
      '-n, --no-output' => dt('Do not dump to stdout'),
      '-y, --yes' => dt('Files are overwritten without confirmation'),
    ),
  );

  $items['autoadmin-update'] = array(
    'callback' => 'autoadmin_drush_update',
    'description' => dt('Updates both .install file and Auto Admin schema.'),
    'arguments' => array('[module] [table1] [table2] ... [tableN]' => dt('First the module, and then all tables to be updated')),
    'options' => array(
      '-w, --write' => dt('Write to [module]/[module].autoadmin.inc'),
      '-n, --no-output' => dt('Do not dump to stdout'),
      '-y, --yes' => dt('Files are overwritten without confirmation'),
      '-t, --tables' => dt('Tables to add'),
    ),
  );
  
  $items['autoadmin-generate-schema'] = array(
    'callback' => 'autoadmin_drush_generate_schemas',
    'description' => "Generate schema.",
    'arguments' => array('[module1] [module2] ... [moduleN]' => dt('Modules selected for schema generation')),
    'options' => array(
      '-w, --write' => dt('Write to [module]/[module].autoadmin.inc'),
      '-n, --no-output' => dt('Do not dump to stdout'),
      '-y, --yes' => dt('Files are overwritten without confirmation'),
    ),
  );

  $items['autoadmin-update schema'] = array(
    'callback' => 'autoadmin_drush_update_schema',
    'description' => "Update schema.",
    'arguments' => array('[module1] [module2] ... [moduleN]' => dt('Modules selected for schema update')),
    'options' => array(
      '-w, --write' => dt('Write to [module]/[module].autoadmin.inc'),
      '-n, --no-output' => dt('Do not dump to stdout'),
      '-y, --yes' => dt('Files are overwritten without confirmation'),
    ),
  );

  $items['autoadmin-generate-install'] = array(
    'callback' => 'autoadmin_drush_generate_install',
    'description' => dt("Generates .install file."),
    'arguments' => array('[module] [table1] [table2] ... [tableN]' => dt('First the module, and then all tables to be added')),
    'options' => array(
      '-w, --write' => dt('Write to [module]/[module].install'),
      '-n, --no-output' => dt('Do not dump to stdout'),
      '-y, --yes' => dt('Files are overwritten without confirmation'),
    ),
  );

  $items['autoadmin-update-install'] = array(
    'callback' => 'autoadmin_drush_update_install',
    'description' => dt("Update hook_schema() in .install file."),
    'arguments' => array('[module] [table1] [table2] ... [tableN]' => dt('First the module, and then all tables to be added. No longer existing tables are removed automatically')),
    'options' => array(
      '-w, --write' => dt('Write to [module]/[module].install'),
      '-n, --no-output' => dt('Do not dump to stdout'),
      '-y, --yes' => dt('Files are overwritten without confirmation'),
    ),
  );
  
  return $items;
}

/**
 * Implementation of hook_drush_help().
 *
 * This function is called whenever a drush user calls
 * 'drush help <name-of-your-command>'.
 *
 * @param $section string
 *   A string with the help section (prepend with 'drush:').
 * @return string
 *   A string with the help text for your command.
 */
function autoadmin_drush_help($section) {
  $section = str_replace('drush:', '', $section);
  $items = autoadmin_drush_command();
  if ($section == 'autoadmin') {
    unset($items['autoadmin']);
    $text = '';
    foreach ($items as $command => $item) {
      $space = str_repeat(' ', 34 - strlen($command));
      $text .= "$command $space $item[description]\n";
    }
    $text = trim($text);
    return
           "-------------------------\n"
      . dt("-- Auto Admin Commands --\n")
      .    "-------------------------\n"
      . $text
      . "\n\nUse ie. 'drush help autoadmin-generate' to get help for each command."
    ;
  }
  if (!empty($items[$section])) {
    return $items[$section]['description'];
  } else {
    return FALSE;
  }
}