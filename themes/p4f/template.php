<?php
// $Id: template.php $

function p4f_theme() {
  return array(
    'user_relationships' => array(
      'arguments' => array('account' => NULL, 'rtid' => NULL),
      'template'  => 'user_relationships',
    ),
    'os_poker_teaser' => array(
      'arguments' => array('text' => ""),
      'template' => 'os-poker-teaser',
    ),
    'os_poker_home_signup' => array(
      'arguments' => array('form' => NULL),
      'template' => 'home_signup',
    ),
    'os_poker_help' => array(
      'template' => 'help.en',
    ),
    'page_front_banner' => array(
      'arguments' => array('id' => NULL, 'text' => NULL, 'href' => NULL)
    ),
    'page_front_banners' => array(
      'arguments' => array(),
      'template' => 'page-front-banners',
    ),
	/* added because of broken module */
	'os_poker_user_brief' => array(
		'arguments' => array('os_user' => NULL),
		'template' => 'user_brief',
	),
	'os_poker_header_user_brief' => array(
		'arguments' => array('os_user' => NULL),
		'template' => 'user_header_brief',
	),
	'os_poker_welcome-splash' => array(
		'arguments' => array('os_user' => NULL),
		'template' => 'welcome-splash',
	),
	'os_poker_home_promotion' => array(
		'arguments' => array('os_user' => NULL),
		'template' => 'home_promotion',
	),
	'os_poker_profile' => array(
		'arguments' => array('target_user' => NULL, "external" => TRUE, 'next_tourney' => NULL, 'tourney_results' => array()),
		'template' => 'profile',
	),
	'os_poker_table_users' => array(
		'arguments' => array('item' => NULL, 'selected' => FALSE),
		'template' => 'table_users',
	),
    'user_login_block' => array(
      'template' => 'user_login',
      'arguments' => array('form' => NULL),
    ),
	'os_poker_languages' => array(
		'arguments' => array('icons' => FALSE),
		'template' => 'language_bar',
	),
    'buddies_list' => array(
      'arguments' => array('buddies' => NULL, 'current_user' => NULL, 'action' => NULL, 'page' => NULL),
      'template'  => 'buddies-list',
    ),
    'poker_error_message' => array(
      'arguments' => array('text' => NULL),
      'template'  => 'error_message',
    ),
	'os_poker_shop_tabs' => array(
		'arguments' => array('active_tab' => NULL, 'content' => NULL),
		'template' => 'shop_tabs',
	),
	'os_poker_footer' => array(
		'arguments' => array(),
		'template' => 'footer',
    ),
    'os_poker_tos' => array(
		'template' => 'imprint.en',
    ),
	/* end of added stuff */
  );
}

function p4f_page_front_banner($id, $text, $href) {
  return l(
    '<span class="banner-inner">'.$text.'</span>',
    $href,
    array('html' => TRUE, 'attributes' => array('class' => 'banner', 'id' => $id, 'title' => $text))
  );
}

function p4f_preprocess_page(&$variables) {
  $language = $variables['language'];
  $variables['body_classes'] .= ' ' . $language->language;
  if (arg(0) == 'poker') {
    $variables['body_classes'] .= ' '. os_poker_clean_css_identifier(arg(0) .'-'. arg(1));
  }
  $language_css = path_to_theme().'/p4f-' . $language->language . '.css';
  if(!file_exists($language_css)) {
    $language_css = path_to_theme().'/p4f-en.css';
  }
  drupal_add_css($language_css, 'theme', 'all', TRUE);
  foreach($variables['template_files'] as $template) {
    $template_css = path_to_theme().'/'.$template.'.css';
    if(file_exists($template_css)) {
      drupal_add_css($template_css, 'theme', 'all', TRUE);
    }
    $language_css = path_to_theme().'/'.$template.'-'.$language->language.'.css';
    if(file_exists($language_css)) {
      drupal_add_css($language_css, 'theme', 'all', TRUE);
    }
  }
  $variables['styles'] = drupal_get_css();

  //Add front page banners
  if($variables['is_front'] && !$variables['logged_in']) {
    $variables['bottom_content'] = theme('page_front_banners');
  }
}

function p4f_preprocess_block(&$variables) {
  $block =& $variables['block'];
  $classes = $variables['classes'];
  if($classes) {
    $classes = explode(' ', $classes);
  }

  $classes[] = 'block';
  $classes[] = css_class($block->module).'-block';
  $classes[] = 'region-'.$variables['block_zebra'];
  $classes[] = 'region-block-'.$variables['block_id'];
  $variables['domid'] = css_class('block-' . $block->module . '-' . $block->delta);
  $variables['classes'] = implode(' ', $classes);
}

function p4f_preprocess_os_poker_help(&$variables) {
  $variables['tutorial'] = base_path() . drupal_get_path('theme', 'p4f') . '/swf/help/Tutorial_002.html';
}

function p4f_poker_tutorial_link() {
  $tutorial = p4f_flash_tutorial();
  return l(t("Click here!"), '#TB_inline', array(
    'external' => TRUE,
    'attributes' => array(
      'class' => 'yellow thickbox',
    ),
    'query' => 'height='. ($tutorial['size'][1]+5) .'&width='. ($tutorial['size'][0]) .'&inlineId=poker-tutorial&',
  ));
}

function p4f_flash_tutorial($filename = 'PokerTutorial') {
  global $language;
  static $file, $size;
  if(!isset($file)) {
      $file = drupal_get_path('theme', 'p4f') .'/swf/'. $filename .'.'. $language->language .'.swf';
    if(!file_exists($file)) {
      $file = drupal_get_path('theme', 'p4f') .'/swf/'. $filename .'.en.swf';
    }
    $size = @getimagesize($file);
  }
  return array('file' => $file, 'size' => $size, 'alt' => t('Sorry, your browser does not support Flash.'));
}

function css_class($string) {
  return str_replace(array(' ', '_'), '-', $string);
}

/**
 * Override theme_menu_item_link().
 *
 * Allow non-link menu entries (using http://none has href).
 */
function p4f_menu_item_link($link) {
  if (empty($link['localized_options'])) {
    $link['localized_options'] = array();
  }
  //FIXME: This shouldn't be used. Please fix #179 and do proper menu items
  //transation as documented on http://drupal.org/node/313302
  $title = $link[title] ? t($link[title]) : $link[title];
  if ($link['href'] == "http://none") {
    return "<a href=\"#\">$title</a>";
  }
  else {
    return l($title, $link['href'], $link['localized_options']);
  }
}
