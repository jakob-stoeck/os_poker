<?php /* this is in fact the contents of the non logged in homepage */ ?>

<div id="poker-teaser">
<h2><?php print t("Poker with your friends and our Bunnys"); ?></h2>
	<h3><?php print t("Learn Texas Hold'em No Limit and win hot prizes!"); ?></h3>
	<a id="register-now" class="open-register-form"><span><?php print t("Register now for free"); ?></span></a>
	<p class="register-description"><?php print t("Experience with us the exciting world of poker! Get your thrills in high stakes games and tournaments and play for millions without any risk."); ?> </p>
	<p class="register-description"><?php print t("The better you play, the greater your chances for a date with a Playmate."); ?></p>
	<a id="register-now-chips" class="open-register-form" rel="nofollow"><span><?php print t("$1.000 Start bonus"); ?></span></a>
	<ul id="explanations">
		<li><a href="<?php print url('poker/help', array('query' => 'height=442&width=603&keepThis=true&TB_iframe=true&'))?>" class="thickbox open-video-tutorial"><?php print t("Poker Tutorial"); ?></a></li>
	    <li><a href="?height=<?php print ($tutorial['size'][1]+10)?>&width=<?php print ($tutorial['size'][0]+5)?>&inlineId=poker-tutorial&#TB_inline" class="thickbox"><?php print t("This is how it works"); ?></a></li>
	</ul>

	<p><small class="legal">* <?php print t("You play in all games with virtual game money with no value."); ?></small></p>

    <div id="poker-tutorial">
        <div class="tb-fix-margin">
            <script src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js" type="text/javascript"></script>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?php print $tutorial['size'][0]?>" height="<?php print $tutorial['size'][1]?>">
                <param name="movie" value="<?php print $tutorial['file']?>" />
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="<?php print $tutorial['file']?>" width="<?php print $tutorial['size'][0]?>" height="<?php print $tutorial['size'][1]?>">
                <!--<![endif]-->
                <!--[if !IE]>-->
                </object>
                <!--<![endif]-->
            </object>
		  </div>
      </div>
</div>

<div id="page-front-banners">
  <a href="<?php print url('poker/help', array('query' => 'height=442&width=603&keepThis=true&TB_iframe=true&#help-tutorial'))?>" class="thickbox banner open-video-tutorial" id="banner-signup" title="<?php print t("This is how it works"); ?>"><span class="banner-inner"><?php print t("This is how it works"); ?></span></a>
  <strong class="banner" id="banner-tournament"><span class="banner-inner"><?php print t("Poker with our Bunnies"); ?></span></strong>
  <a href="/drupal6/?q=de/poker/pages/tourneyinfo" class="banner" id="banner-join" title="<?php print t("Join the world&#039;s sexiest poker!"); ?>"><span class="banner-inner">Join the world's <strong>sexiest poker!</strong></span></a>
</div>

