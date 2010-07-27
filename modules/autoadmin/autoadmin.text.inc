<?php
// $Id: autoadmin.text.inc,v 1.1 2009/12/06 21:29:13 rune Exp $

/**
 * @file
 * Pluralize and singularize English nouns and other textual helpers.
 *
 * @ingroup autoadmin
 * @credit autoadmin_text_pluralize() and autoadmin_text_singularize() are taken from the Akelos PHP Framework. See http://www.akelos.org.
 */

/**
 * Pluralizes English nouns.
 *
 * @param string $word
 *   English noun to pluralize.
 * @return string
 */
function autoadmin_text_pluralize($word) {
  $plural = array(
    '/(quiz)$/i' => '\1zes',
    '/^(ox)$/i' => '\1en',
    '/([m|l])ouse$/i' => '\1ice',
    '/(matr|vert|ind)ix|ex$/i' => '\1ices',
    '/(x|ch|ss|sh)$/i' => '\1es',
    '/([^aeiouy]|qu)ies$/i' => '\1y',
    '/([^aeiouy]|qu)y$/i' => '\1ies',
    '/(hive)$/i' => '\1s',
    '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
    '/sis$/i' => 'ses',
    '/([ti])um$/i' => '\1a',
    '/(buffal|tomat)o$/i' => '\1oes',
    '/(bu)s$/i' => '\1ses',
    '/(alias|status)/i' => '\1es',
    '/(octop|vir)us$/i' => '\1i',
    '/(ax|test)is$/i' => '\1es',
    '/s$/i' => 's',
    '/$/' => 's');
  $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep');
  $irregular = array(
    'person' => 'people',
    'man' => 'men',
    'child' => 'children',
    'sex' => 'sexes',
    'move' => 'moves');
  $lowercased_word = drupal_strtolower($word);
  foreach ($uncountable as $_uncountable) {
    if (drupal_substr($lowercased_word, (-1 * drupal_strlen($_uncountable))) == $_uncountable) {
      return $word;
    }
  }
  foreach ($irregular as $_plural => $_singular) {
    if (preg_match('/(' . $_plural . ')$/i', $word, $arr)) {
      return preg_replace('/(' . $_plural . ')$/i', drupal_substr($arr[0], 0, 1) . drupal_substr($_singular, 1), $word);
    }
  }
  foreach ($plural as $rule => $replacement) {
    if (preg_match($rule, $word)) {
      return preg_replace($rule, $replacement, $word);
    }
  }
  return FALSE;

}

/**
 * Singularizes English nouns.
 *
 * @param string $word
 *   English noun to singularize.
 * @return string
 */
function autoadmin_text_singularize($word) {
  $singular = array(
    '/(quiz)zes$/i' => '\\1',
    '/(matr)ices$/i' => '\\1ix',
    '/(vert|ind)ices$/i' => '\\1ex',
    '/^(ox)en/i' => '\\1',
    '/(alias|status)es$/i' => '\\1',
    '/([octop|vir])i$/i' => '\\1us',
    '/(cris|ax|test)es$/i' => '\\1is',
    '/(shoe)s$/i' => '\\1',
    '/(o)es$/i' => '\\1',
    '/(bus)es$/i' => '\\1',
    '/([m|l])ice$/i' => '\\1ouse',
    '/(x|ch|ss|sh)es$/i' => '\\1',
    '/(m)ovies$/i' => '\\1ovie',
    '/(s)eries$/i' => '\\1eries',
    '/([^aeiouy]|qu)ies$/i' => '\\1y',
    '/([lr])ves$/i' => '\\1f',
    '/(tive)s$/i' => '\\1',
    '/(hive)s$/i' => '\\1',
    '/([^f])ves$/i' => '\\1fe',
    '/(^analy)ses$/i' => '\\1sis',
    '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\\1\\2sis',
    '/([ti])a$/i' => '\\1um',
    '/(n)ews$/i' => '\\1ews',
    '/s$/i' => '',
  );
  $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep', 'sms');
  $irregular = array(
    'person' => 'people',
    'man' => 'men',
    'child' => 'children',
    'sex' => 'sexes',
    'move' => 'moves');
  $lowercased_word = drupal_strtolower($word);
  foreach ($uncountable as $_uncountable) {
    if (drupal_substr($lowercased_word, (-1 * drupal_strlen($_uncountable))) == $_uncountable) {
      return $word;
    }
  }
  foreach ($irregular as $_singular => $_plural) {
    if (preg_match('/(' . $_plural . ')$/i', $word, $arr)) {
      return preg_replace('/(' . $_plural . ')$/i', drupal_substr($arr[0], 0, 1) . drupal_substr($_singular, 1), $word);
    }
  }
  foreach ($singular as $rule => $replacement) {
    if (preg_match($rule, $word)) {
      return preg_replace($rule, $replacement, $word);
    }
  }
  return $word;
}

/**
 * Returns a title generated from name of table.
 *
 * @param string $table
 * @param int $count
 *   If count is bigger than 1 return pluralized.
 * @return string
 */
function autoadmin_text_from_table($table) {
  $table = str_replace('_', ' ', $table);
  $table = ucwords($table);
  return $table;
}

/**
 * Removes _id from database field name and returns uppercased title.
 *
 * @param string $title
 * @return string
 */
function autoadmin_text_from_field($title) {
  $title = str_replace('_id', '', $title);
  $title = str_replace('_', ' ', $title);
  $title = ucwords($title);
  return $title;
}