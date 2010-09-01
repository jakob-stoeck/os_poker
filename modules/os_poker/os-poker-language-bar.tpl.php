<?php
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
?>
<div class="language-bar">
	<?php
	if (variable_get('language_count', 1) > 1 && variable_get('language_negotiation', LANGUAGE_NEGOTIATION_NONE) != LANGUAGE_NEGOTIATION_NONE)
	{
		global $language;
	
		$path = drupal_is_front_page() ? '<front>' : $_GET['q'];
		$languages = language_list('enabled');
		$links = array();
		$output = array();

		/*
		foreach ($languages[1] as $lang) 
		{
			$name = $lang->language;
			$links[$name] = array(
				'href'       => $path,
				'title'      => $lang->native,
				'language'   => $lang,
				'attributes' => array('class' => 'language-link'),
			);
			
			drupal_alter('translation_link', $links, $path);
			
			if ($icons == TRUE)
			{
				$img = "<img src=\"" . $base_path . drupal_get_path('module', 'os_poker') . "/images/{$name}.png\" title=\"" . $links[$name]["title"] . "\" alt=\"" . $links[$name]["title"] . "\"/>";
			}
			else
			{
				$links[$name]['attributes']['class'] .= " text";
				$img = $links[$name]["title"];
			}
			
			if ($language->language == $lang->language)
			{
				$links[$name]['attributes']['class'] .= " current";
			}
			
			$output []= l($img, $links[$name]["href"], array("attributes" => $links[$name]["attributes"],	'language' => $links[$name]["language"], "html" => $icons)) ;
		}
		*/
		
		if ($icons == TRUE)
		{
			print implode("", $output);
      if (user_is_logged_in()) {
        print l(t('Logout'), 'logout', array('attributes'=> array('class' => 'logout')));
      }
		}
		else
		{
			print implode("&nbsp;|", $output);
		}
	}
	?>
</div>
