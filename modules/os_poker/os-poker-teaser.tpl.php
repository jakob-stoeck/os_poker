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
<div id="home_teaser">
	<div class="home_title">
		<img src="<?php print drupal_get_path('module', 'os_poker'); ?>/images/signup_title_left.gif" alt="<?php print t("Play Texas Hold'em Poker with your Friends. Get FREE Pokerchips every day that you play !"); ?>"/>
	</div>
	<div>
		<div class="home_teaser_left">
			<img src="<?php print drupal_get_path('module', 'os_poker'); ?>/images/poker_table.png" alt="Poker Table"/>
		</div>
		<div class="home_teaser_right">
			<img src="<?php print drupal_get_path('module', 'os_poker'); ?>/images/signup_man.png" alt="Welcome"/>
			<div class="home_teaser_text">
				<?php print $text; ?>
			</div>
		</div>
	</div>
</div>
