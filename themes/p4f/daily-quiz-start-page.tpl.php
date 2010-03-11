<?php
// $Id$
/*
 * @file
 * Template override for dailyquiz start page.
 *
 * This file is part of quiz_integration.
 *
 * daily_quiz is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * daily_quiz is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with daily_quiz.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<? // if the game has been played ?>
<?php if ($passed): ?>
	<h2 class="title_bar">Dein Ergebnis</h2>
	<div id="quiz" class="news_content">
		<p><?php print $summary ?></p>
	</div>
<? // this would be the starting point ?>
<?php else :?>
	<h2 class="title_bar">Fashion Quiz</h2>
	<div id="quiz" class="news_content">
		<p><?php print $intro; ?></p>
		<?php if ($time_limit): ?>
			<p><?php print $time_limit; ?></p>
			<?php if($price): ?>
			<h2>TIME LIMITE</h2>
				<p>Price: <?php print $price ?></p>
			<?php endif; ?>
		<?php endif; ?>
		<?php print $link; ?>
	</div>
<?php endif;?>


<? // this part is static, information from here should come from the same source as for the start page (with minor styling diff so not the same template) ?>
<? // only displayed in case the game is not being played: http://drupal-dev.pokersource.info/p4f/wiki/Quiz ?>

<?php if ($passed) { ?>
<div id="quiz_games_teaser" class="news_content">
	<h2 class="fleft">Games</h2><div id="quiz_games_teaser_title"></div>
	<div class="games_hp">
		<img alt="" src="sites/all/themes/p4f/images/games_fashion_poker.png"/>
		<h3>Fashion Poker</h3>
		<p>This text will be the "teasertext", that should be about 2 lines according to the do. On this page it will but cut to about 4...</p>
		<a href="#" class="submit_get">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<img alt="" src="sites/all/themes/p4f/images/games_cartingo.png"/>
		<h3>Cartingo</h3>
		<p>This text will be the "teasertext", that should be about 2 lines according to the do. On this page it will but cut to about 4...</p>
		<a href="#" class="submit_get">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<img alt="" src="sites/all/themes/p4f/images/games_nextdj.png"/>
		<h3>Be the next DJ</h3>
		<p>This text will be the "teasertext", that should be about 2 lines according to the do. On this page it will but cut to about 4...</p>
		<a href="#" class="submit_get">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<img alt="" src="sites/all/themes/p4f/images/games_fashion_quiz.png"/>
		<h3>Fashion Quiz</h3>
		<p>This text will be the "teasertext", that should be about 2 lines according to the do. On this page it will but cut to about 4...</p>
		<a href="#" class="submit_get">Jetzt Spielen</a>
	</div>
	<div class="clear"/></div>

	<div id="quiz_next">
		<a class="fright" href="#" alt="Next game"><span class="hidden">Next game</span></a>
	</div>

    <div class="clear"/></div>
</div>
<? } ?>

<? // buddylist ? ?>

<? //include('buddies-list.tpl.php'); ?>


