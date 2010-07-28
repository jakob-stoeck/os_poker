<?php
// $Id: autoadmin.theme.inc,v 1.3 2009/12/07 13:52:20 rune Exp $

/**
 * @file
 * Theme functions.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Implementation of hook_theme().
 */
function autoadmin_theme() {
  return array(
    'autoadmin_operations' => array('arguments' => array('text' => NULL, 'origin' => NULL)),
    'autoadmin_cascading_deletes' => array('arguments' => array('text' => NULL, 'origin' => NULL)),
    'autoadmin_description' => array('arguments' => array('text' => NULL, 'origin' => NULL)),
  );
}

/**
 * Render a operation link. If more than one link add padding between them.
 *
 * @ingroup themeable
 * @param array $operations
 * @return string html
 */
function theme_autoadmin_operations($operations) {
  $html = '';
  $base_path = base_path();
  $query = !empty($operation['query']) ? $operation['query'] : array();
  foreach ($operations as $operations => $operation) {
    $operation['title'] = str_replace(' ', '&nbsp;', $operation['title']);
    $html .= l(
      $operation['title'],
      $operation['href'],
      array(
        'query' => $query,
        'attributes' => array('class' => 'operations'),
        'html' => TRUE,
      )
    );
  }
  return $html;
}



/**
 * Renders an unordered list for cascading_deletes.
 *
 * @ingroup themeable
 * @param array $rows
 * @param array $relation
 * @return string html
 */
function theme_autoadmin_cascading_deletes($rows, $relation) {
  $html = '<ul>';
  $foreign_alias = $relation['foreign_alias'];
  foreach ($rows as $row) {
    $html .= '<li>' . $row[$foreign_alias] . '</li>';
  }
  $html .= '</ul>';
  return $html;
}

/**
 * Renders description of a schema.
 *
 * @ingroup themeable
 * @param <type> $schema
 * @return <type>
 */
function theme_autoadmin_description($schema) {
  return $schema['title_plural'] . 
    (!empty($schema['description'])
      ? ' <span class="autoadmin_description">(' . trim($schema['description'], '.') . ')</span>'
      : ''
    );
}