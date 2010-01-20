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
    'page_front_banner' => array(
      'arguments' => array('id' => NULL, 'text' => NULL, 'href' => NULL)
    ),
    'page_front_banners' => array(
      'arguments' => array(),
      'template' => 'page-front-banners',
    ),
  );
}

function pbpoker_page_front_banner($id, $text, $href) {
  return l(
    '<span class="banner-inner">'.$text.'</span>',
    $href,
    array('html' => TRUE, 'attributes' => array('class' => 'banner', 'id' => $id, 'title' => $text))
  );
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

  //Add front page banners
  if($variables['is_front'] && !$variables['logged_in']) {
    $variables['bottom_content'] = theme('page_front_banners');
  }
}

function pbpoker_preprocess_page_front_banners(&$variables)
{
  $variables['banners'][] = theme('page_front_banner', 'banner-signup', t('Sign up now and get a bonus! <strong>$1000 Chips</strong>'), '');
  $variables['banners'][] = theme('page_front_banner', 'banner-tournament',  t('$1Mio. chips tournament!'), '');
  $variables['banners'][] = theme('page_front_banner', 'banner-join', t('Join the world\'s <strong>sexiest poker!</strong>'), '');
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
  $variables['table'] = theme('image', $theme_path.'/images/teaser-table.jpg', t('Poker Table'), '', array('id' => 'poker-teaser-table'));
  $variables['girl'] = theme('image', $theme_path.'/images/teaser-girl.gif', '', '', array('id' => 'poker-teaser-girl'));
}

function css_class($string) {
  return str_replace(array(' ', '_'), '-', $string);
}