<?php
// $Id: template.php $
//
//    Copyright (C) 2009, 2010 Pokermania
//    Copyright (C) 2010 OutFlop
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
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
// Picked up from http://drupal-demo.pokersource.info/z2/attachment/ticket/71/Playboy_IVW_Script.txt
// and http://drupal-demo.pokersource.info/z2/ticket/180
  $variables['analytic_scripts'] = "
<script type=\"text/javascript\">
var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");
document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));
</script>
<script type=\"text/javascript\">
try {
var pageTracker = _gat._getTracker(\"UA-15325887-1\");
pageTracker._trackPageview();
} catch(err) {}</script>

<script type=\"text/javascript\"><!--var agof = '10342';//--></script><!-- SZM VERSION=\"1.5\" -->
<script type=\"text/javascript\">
	<!--
	if(typeof agof === 'undefined'){
		var agof = 10342;
	}
	document.write('<img id=\"ivwpx1\" SRC=\"http://playboy.ivwbox.de/cgi-bin/ivw/CP/' + agof + ';?r=' + escape(document.referrer) + '&d=' + (Math.random() * 100000) + '\" width=\"1\" height=\"1\" border=\"0\" alt=\"\" style=\"display: none;\" />');
	// -->
</script>
<noscript>
	<img src=\"http://playboy.ivwbox.de/cgi-bin/ivw/CP/10342\" width=\"1\" height=\"1\" alt=\"\" style=\"display: none;\" />
</noscript>
";
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
  $variables['card'] = pbpoker_flash_tutorial('SignUP');
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
  static $file = array(), $size = array();
  if(!isset($file[$filename])) {
      $file[$filename] = drupal_get_path('theme', 'pbpoker') .'/swf/'. $filename .'.'. $language->language .'.swf';
    if(!file_exists($file[$filename])) {
      $file[$filename] = drupal_get_path('theme', 'pbpoker') .'/swf/'. $filename .'.en.swf';
    }
    $size[$filename] = @getimagesize($file[$filename]);
  }
  return array('file' => $file[$filename], 'size' => $size[$filename], 'alt' => t('Sorry, your browser does not support Flash.'));
}

function pbpoker_preprocess_os_poker_first_profile(&$variables) {
  $variables['subtitle'] = t("Full player data will also be rewarded with 2,000 poker chips!");
  $variables['footertitle'] = t("Our tip:");
  $variables['footer'] = t("For every friend you successfully invite to Playboy Poker, you and your friend collect additional poker chips.");
}

function pbpoker_daily_gift($amount = 100) {
  $current_user = CUserManager::instance()->CurrentUser();

  $enable = $current_user && $current_user->CanDailyGift();
  $output = '';
  if ($enable) {
    $output .= '<div id="today_gift">';
    $output .= t('Send <strong> !amount free chips</strong> to your poker buddies!', array('!amount' => $amount,));
    $output .= theme('button', array(
      '#button_type' => 'button',
      '#id' => 'today-gift-button',
      '#value' => t('Send'),
    ));
    $output .= '</div>';
  }
  $output .= '<div id="today_gift_invite"';
  if($enable) {
    $output .= ' style="display: none;"';
  }
  $output .= '>';
  $output .= theme('poker_image', 'invite_more_friends.jpg', t('Invite more friends'));
  $output .= '</div>';
  return $output;
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

