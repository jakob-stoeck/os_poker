<?php
// $Id: template.php $

/**
 * TODO: Is this needed. These are overrides for module's template. Adding
 *       .tpl.php files should ne enough (unless they need to renamed but
 *       it is probably not really needed).
 */
function poker_theme() {
  return array(
    'user_login_block' => array(
      'template' => 'user_login',
      'arguments' => array('form' => NULL),
    ),
    'user_relationships' => array(
      'arguments' => array('account' => NULL, 'rtid' => NULL),
      'template'  => 'user_relationships',
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




function poker_status_messages($display = NULL) {
  $thickbox = arg(0) !== 'admin';
  $output = '';
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"messages $type\">\n";
    if($thickbox && $type == "error") {
      $output .= '<span class="header"><strong>'.t('Sorry!').'</strong> '.format_plural(count($messages), 'An error occured', 'Errors occured').":</span>";
    }
    if (($count = count($messages)) > 1) {
      $output .= " <ul>\n";
      $idx = 0;
      foreach ($messages as $message) {
        $output .= '  <li>'. $message ."</li>\n";
        $idx++;
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    
    $output .= "</div>\n";
  }
  return $output;
}
/**
 * Override theme_button()
 */
function poker_button($element) {
  if (isset($element['#attributes']['class'])) {
    $element['#attributes']['class'] = 'poker_submit form-submit form-'. $element['#button_type'] .' '. $element['#attributes']['class'];
  }
  else {
    $element['#attributes']['class'] = 'poker_submit form-submit form-'. $element['#button_type'];
  }
  //$output[] = $element['#prefix'];
  $output[] = '<div id="'. $element['#id'] . '" ' . drupal_attributes($element['#attributes']).'">';
  $output[] = '<div class="pre">&nbsp;</div>';
	$output[] = '<div class="label">';
	$output[] = '<input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ') .'" value="'. check_plain($element['#value']) .'"/>';
	$output[] = '</div>';
	$output[] = '<div class="user_login_clear"></div>';
	$output[] = '</div>';
  //$output[] = $element['#suffix'];

  return implode("\n", $output);
}