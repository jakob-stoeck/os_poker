<?php // Added by n4mu to simplify (i hope)
$cfg_theme_path = drupal_get_path("theme", "pbpoker")?>

<? // original stuff: commented out ?>
          <!--<div id="page-front-banners">
            <?php //foreach($banners as $banner) { print $banner; } ?>
          </div> -->

<? // Static home page things ?>
<div class="white_boxes">
	<h2 class="title_bar">Preise</h2>
	<div class="fleft prize_box">
		<img class="fleft" src="<? print $cfg_theme_path; ?>/images/prize_hits.png" />
		<div class="prize_txt">
			<h3>Tagespreis</h3>
			<p>Lorem text lol bonjour</p>
		</div>
	</div>
	<div class="fright prize_box">
		<div class="fleft">
			<img src="<? print $cfg_theme_path; ?>/images/prize_cdpack.png" />
		</div>
		<div class="prize_txt">
			<h3>Wochenpreis</h3>
			<p>Lorem text lol bonjour</p>
		</div>
	</div>
	<div class="fleft prize_box">
		<div class="fleft">
			<img src="<? print $cfg_theme_path; ?>/images/prize_fashion.png" />
		</div>
		<div class="prize_txt">
			<h3>Monatpreis</h3>
			<p>Lorem text lol bonjour</p>
		</div>
	</div>
	<div class="fright prize_box">
		<div class="fleft">
			<img src="<? print $cfg_theme_path; ?>/images/prize_tbo.png" />
		</div>
		<div class="prize_txt">
			<h3>Jahrespreis 2010</h3>
			<p>Lorem text lol bonjour</p>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div class="white_boxes">
	<h2 class="title_bar">Games</h2>
	<div class="games_hp">
		<? // this should be dynamic, 1 out of 7 games ?>
		<img src="<? print $cfg_theme_path; ?>/images/games_fashion_poker.png" alt=""/>
		<h3>Fashion Poker</h3>
		<p>This text will be the "teasertext", that should be about 2 lines according to the do. On this page
		it will but cut to about 4...</p>
		<a class="submit_get" href="#">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<? // this should be dynamic, 1 out of 7 games ?>
		<img src="<? print $cfg_theme_path; ?>/images/games_cartingo.png" alt=""/>
		<h3>Cartingo</h3>
		<p>This text will be the "teasertext", that should be about 2 lines according to the do. On this page
		it will but cut to about 4...</p>
		<a class="submit_get" href="#">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<? // this should be dynamic, 1 out of 7 games ?>
		<img src="<? print $cfg_theme_path; ?>/images/games_nextdj.png" alt=""/>
		<h3>Be the next DJ</h3>
		<p>This text will be the "teasertext", that should be about 2 lines according to the do. On this page
		it will but cut to about 4...</p>
		<a class="submit_get" href="#">Jetzt Spielen</a>
	</div>
	<div class="games_hp">
		<? // this should be dynamic, 1 out of 7 games ?>
		<img src="<? print $cfg_theme_path; ?>/images/games_fashion_quiz.png" alt=""/>
		<h3>Fashion Quiz</h3>
		<p>This text will be the "teasertext", that should be about 2 lines according to the do. On this page
		it will but cut to about 4...</p>
		<a class="submit_get" href="#">Jetzt Spielen</a>
	</div>
	<div class="clear"></div>
</div>
<p class="seo_text">Du interessierst dich für Fashion und online Games bei denen Du wertvolle Gewinne gratis erspielen kannst, dann bist Du bei Play4fashion genau auf der richtigen Spielfläche. Wir bieten online Poker, Flipper, Air Hockey, Lady Gaga Musik Spiele, Quiz und mehr. Gewinne gratis online Preise aus der Fashion Welt von NewYorker. Registriere Dich jetzt kostenlos und gewinne noch heute.</p>
