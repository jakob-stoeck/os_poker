<?php // Added by n4mu to simplify (i hope)
$cfg_theme_path = drupal_get_path("theme", "p4f")?>

<? // Static home page things ?>
<div class="white_boxes">
	<h2 class="title_bar">Preise</h2>
	<div class="fleft prize_box">
		<img class="fleft" src="<? print $cfg_theme_path; ?>/images/prize_hits.png" />
		<div class="prize_txt">
			<h3><?php print t('Daily Prize') ?></h3>
			<p><?php print t('Description of the Daily Prize for front page.') ?></p>
		</div>
	</div>
	<div class="fright prize_box">
		<div class="fleft">
			<img src="<? print $cfg_theme_path; ?>/images/prize_cdpack.png" />
		</div>
		<div class="prize_txt">
			<h3><?php print t('Weekly Prize') ?></h3>
			<p><?php print t('Description of the Weekly Prize for front page.') ?></p>
		</div>
	</div>
	<div class="fleft prize_box">
		<div class="fleft">
			<img src="<? print $cfg_theme_path; ?>/images/prize_fashion.png" />
		</div>
		<div class="prize_txt">
			<h3><?php print t('Monthly Prize') ?></h3>
			<p><?php print t('Description of the Monthly Prize for front page.') ?></p>
		</div>
	</div>
	<div class="fright prize_box">
		<div class="fleft">
			<img src="<? print $cfg_theme_path; ?>/images/prize_tbo.png" />
		</div>
		<div class="prize_txt">
			<h3><?php print t('Yearly Prize') ?> 2010</h3>
			<p><?php print t('Description of the Yearly Prize for front page.') ?></p>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div class="white_boxes">
	<h2 class="title_bar">Games</h2>
	<div class="games_hp">
		<? // this should be dynamic, 1 out of 7 games ?>
		<img src="<? print $cfg_theme_path; ?>/images/games_fashion_poker.png" alt=""/>
		<h3><?php print t('Fashion Poker')?></h3>
		<p><?php print t('Description of the Fashion Poker game for front page.') ?></p>
		<a class="submit_get" href="#">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<? // this should be dynamic, 1 out of 7 games ?>
		<img src="<? print $cfg_theme_path; ?>/images/games_cartingo.png" alt=""/>
		<h3><?php print t('Cartingo') ?></h3>
		<p><?php print t('Description the Cartingo game for front page.') ?></p>
		<a class="submit_get" href="#">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<? // this should be dynamic, 1 out of 7 games ?>
		<img src="<? print $cfg_theme_path; ?>/images/games_nextdj.png" alt=""/>
		<h3><?php print t('Be the next DJ') ?></h3>
		<p><?php print t('Description of the Be the next DJ game for front page.') ?></p>
		<a class="submit_get" href="#">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<? // this should be dynamic, 1 out of 7 games ?>
		<img src="<? print $cfg_theme_path; ?>/images/games_fashion_quiz.png" alt=""/>
		<h3><?php print t('Fashion Quiz')?></h3>
		<p><?php print t('Description of the Fashion Quiz game for front page.') ?></p>
		<a class="submit_get" href="#">Jetzt Spielen</a>
	</div>
	<div class="clear"></div>
</div>
<p class="seo_text"<?php print ('SEO Test for the front page.') ;?></p>
