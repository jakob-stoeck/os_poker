<div class="language-bar">
	<?php
	if (variable_get('language_count', 1) > 1 && variable_get('language_negotiation', LANGUAGE_NEGOTIATION_NONE) != LANGUAGE_NEGOTIATION_NONE)
	{
		global $language;

		$path = drupal_is_front_page() ? '<front>' : $_GET['q'];
		$languages = language_list('enabled');
		$links = array();
		$output = array();

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
				$img = "<img src=\"" . drupal_get_path('module', 'os_poker') . "/images/{$name}.png\" title=\"" . $links[$name]["title"] . "\" alt=\"" . $links[$name]["title"] . "\"/>";
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

		if ($icons == TRUE)
		{
			print implode("", $output);
		}
		else
		{
			print implode("&nbsp;|", $output);
		}
	}
	?>
</div>
