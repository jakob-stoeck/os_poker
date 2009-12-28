<?php
// $Id: template.php $

function poker_theme()
{
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


function poker_preprocess_user_login_block(&$variables)
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
	$variables['f_links'] = l(t("Forgot your Password ?"), "poker/forgot-password", array(	"attributes" => array(
																													"title" => t("Request new password via e-mail") . ".",
																													"id" => "forgot_password",
																													"class" => "thickbox",
																												),
																								"query" => array(
																												"height" => "187",
																												"width" => "382",
																												"keepThis" => "true",
																												"TB_iframe" => "true",
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
