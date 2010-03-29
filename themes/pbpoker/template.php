<?php
// $Id: template.php $

function pbpoker_theme() {
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
  );
}
function pbpoker_preprocess_page(&$variables) {
  $language = $variables['language'];
  $variables['body_classes'] .= ' ' . $language->language;
  if (arg(0) == 'poker') {
    $variables['body_classes'] .= ' '. os_poker_clean_css_identifier(arg(0) .'-'. arg(1));
    if (arg(1) == 'pages') {
	/* handle static pages correcty */
	$variables['body_classes'] .= ' '. os_poker_clean_css_identifier(arg(0) .'-'. arg(2));
    }
  }
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
  $variables['footer_scripts'] = drupal_get_js('footer');
  $variables['special_scripts'] = drupal_get_js('special');
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
  global $language;
  $theme_path = drupal_get_path('theme', 'pbpoker');
  $variables['tutorial'] = pbpoker_flash_tutorial();
}

function pbpoker_preprocess_os_poker_help(&$variables) {
  $variables['tutorial'] = drupal_get_path('theme', 'pbpoker') . '/swf/help/Tutorial_002.html';
}

function pbpoker_poker_tutorial_link() {
  $tutorial = pbpoker_flash_tutorial();
  return l(t("Click here!"), '#TB_inline', array(
    'external' => TRUE,
    'attributes' => array(
      'class' => 'tutorial thickbox',
    ),
    'query' => 'height='. ($tutorial['size'][1]+5) .'&width='. ($tutorial['size'][0]) .'&inlineId=poker-tutorial&',
  ));
}

function pbpoker_flash_tutorial($filename = 'PokerTutorial') {
  global $language;
  static $file, $size;
  if(!isset($file)) {
      $file = drupal_get_path('theme', 'pbpoker') .'/swf/'. $filename .'.'. $language->language .'.swf';
    if(!file_exists($file)) {
      $file = drupal_get_path('theme', 'pbpoker') .'/swf/'. $filename .'.en.swf';
    }
    $size = @getimagesize($file);
  }
  return array('file' => $file, 'size' => $size, 'alt' => t('Sorry, your browser does not support Flash.'));
}

function css_class($string) {
  return str_replace(array(' ', '_'), '-', $string);
}

function css_using_cdn($styles) {
		$lines = preg_split('/[\r\n]+/', $styles);
			$newlines = array();
			foreach ($lines as $line) {
						if (preg_match('/^(.*href=[\'"])([^\'"]+)([\'"].*)$/', $line, $m)) {
										$line = $m[1] . simplecdn_rewrite_url($m[2], 'css') . $m[3];
													$newlines[] = $line;
												}
							}

				return join("\n", $newlines);
}

