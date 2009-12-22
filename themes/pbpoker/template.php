<?php
// $Id: template.php $

function pbpoker_theme() {
  return array(
    'user_login_block' => array(
      'template' => 'user_login',
      'arguments' => array('form' => NULL),
    ),
    'user_relationships' => array(
      'arguments' => array('account' => NULL, 'rtid' => NULL),
      'template'  => 'user_relationships',
    ),
    'buddy_block' => array(
      'arguments' => array('buddy' => NULL, 'buddyNumber' => NULL, 'hide_links' => FALSE),
      'template'  => 'buddy_list_block',
    ),
    'buddies_tabs' => array(
      'arguments' => array('action' => NULL),
      'template'  => 'buddies_tabs',
    ),
    'buddies_list' => array(
      'arguments' => array('buddies' => NULL, 'current_user' => NULL, 'action' => NULL, 'page' => NULL),
      'template'  => 'buddies_list',
    ),
    'buddies_invite' => array(
      'arguments' => array('form' => NULL, 'current_user' => NULL),
      'template'  => 'buddies_invite',
    ),
    'buddies_invitedlist' => array(
      'arguments' => array('current_user' => NULL),
      'template'  => 'buddies_invitedlist',
    ),
    'buddies_search' => array(
      'arguments' => array('form' => NULL, 'current_user' => NULL),
      'template'  => 'buddies_search',
    ),
    'poker_error_message' => array(
      'arguments' => array('text' => NULL),
      'template'  => 'error_message',
    ),
    'os_poker_teaser' => array(
      'arguments' => array('text' => ""),
      'template' => 'os-poker-teaser',
    ),
  );
}

function phptemplate_username($object) {

  if ($object->uid && $object->name) {
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($object->name) > 20) {
      $name = drupal_substr($object->name, 0, 15) .'...';
    }
    else {
      $name = $object->name;
    }

    if (user_access('access user profiles')) {
      $output = l($name, 'poker/profile/profile/'. $object->uid, array('title' => t('View user profile.'), "query" => array("height" => 442,
																															"width" => 603,
																															"TB_iframe" => "true"),
																											"attributes" => array("class" => "thickbox")));
    }
    else {
      $output = check_plain($name);
    }
  }
  else if ($object->name) {
    // Sometimes modules display content composed by people who are
    // not registered members of the site (e.g. mailing list or news
    // aggregator modules). This clause enables modules to display
    // the true author of the content.
    if ($object->homepage) {
      $output = l($object->name, $object->homepage);
    }
    else {
      $output = check_plain($object->name);
    }

    $output .= ' ('. t('not verified') .')';
  }
  else {
    $output = variable_get('anonymous', t('Anonymous'));
  }

  return $output;
}


function pbpoker_preprocess_page(&$variables) {
  $language = $variables['language'];
  $variables['body_classes'] .= ' ' . $language->language;
  $language_css = path_to_theme().'/pbpoker-' . $language->language . '.css';
  if(!file_exists($language_css)) {
    $language_css = path_to_theme().'/pbpoker-en.css';
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
}

function pbpoker_preprocess_block(&$variables) {
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

function pbpoker_preprocess_os_poker_teaser(&$variables) {
  $theme_path = drupal_get_path('theme', 'pbpoker');
  $variables['title'] = t('Play Texas Hold\'em Poker with your Fiends.');
  $variables['subtitle'] = t('Get <strong>free</strong> Pokerchips every day that you play!');
  $variables['table'] = theme('image', $theme_path.'/images/teaser-table.gif', t('Poker Table'), '', array('id' => 'poker-teaser-table'));
  $variables['girl'] = theme('image', $theme_path.'/images/teaser-girl.gif', '', '', array('id' => 'poker-teaser-girl'));
}

function pbpoker_preprocess_user_login_block(&$variables)
{
	$class = "";
	$attr = & $variables['form']['pass']['#attributes'];

	if (isset($attr) && isset($attr["class"]))
		$class = $attr["class"];

	$attr["class"] = $class . " custom_input";
	$variables['form']['pass']['#title'] = '';

	$class = "";
	$attr = & $variables['form']['name']['#attributes'];

	if (isset($attr) && isset($attr["class"]))
		$class = $attr["class"];

	$attr["class"] = $class . " custom_input";
	$variables['form']['name']['#title'] = '';

	$variables['f_name'] = drupal_render($variables['form']['name']);
	$variables['f_pass'] = drupal_render($variables['form']['pass']);
	$variables['f_links'] = l(t("Forgot your Password") . "?", "poker/forgot-password", array(	"attributes" => array(
																													"title" => t("Request new password via e-mail") . ".",
																													"id" => "forgot_password",
																												),
																								"query" => array(
																												"height" => "187",
																												"width" => "380",
																										),
																						));

	if (isset($variables['form']['remember_me']))
	{
		$variables['f_remember_me'] = drupal_render($variables['form']['remember_me']);
		unset($variables['form']['remember_me']);
	}

	unset($variables['form']['name']);
	unset($variables['form']['links']);
	unset($variables['form']['pass']);

	$variables['rendered'] .= drupal_render($variables['form']);
}

function css_class($string) {
  return str_replace(array(' ', '_'), '-', $string);
}