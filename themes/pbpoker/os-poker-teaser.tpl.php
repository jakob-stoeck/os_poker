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
/* this is in fact the contents of the non logged in homepage */ ?>

<div id="poker-teaser">
<h2><?php print t("Poker with your friends and our Bunnys"); ?></h2>
	<h3><?php print t("Learn Texas Hold'em No Limit and win hot prizes!"); ?></h3>
	<div id="register-now" class="open-register-form">
    <div style="margin: -10px">
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?php print $card['size'][0]?>" height="<?php print $card['size'][1]?>">
        <param name="movie" value="<?php print $card['file']?>" />
        <param name="flashvars" value="link=javascript:pb_open_registration_form();"/>
        <param name="quality" value="high" />
        <param name="align" value="middle" />
        <param name="wmode" value="transparent" />
        <param name="play" value="true"/>
        <param name="loop" value="true"/>
        <param name="scale" value="showall"/>
        <param name="bgcolor" value="#00ff00"/>
        <param name="allowFullScreen" value="false"/>
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="<?php print $card['file']?>" width="<?php print $card['size'][0]?>" height="<?php print $card['size'][1]?>">
          <param name="flashvars" value="link=javascript:pb_open_registration_form();"/>
          <param name="quality" value="high" />
          <param name="align" value="middle" />
          <param name="wmode" value="transparent" />
          <param name="play" value="true"/>
          <param name="loop" value="true"/>
          <param name="scale" value="showall"/>
          <param name="bgcolor" value="#00ff00"/>
          <param name="allowFullScreen" value="false"/>
          <!--<![endif]-->
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
      <script type="text/javascript">
        function pb_open_registration_form() {
          OsPoker.inlineThickbox('middle-content-right', {width: 410, height: 420});
        }
      </script>
    </div>
  </div>
	<p class="register-description"><?php print t("Experience with us the exciting world of poker! Get your thrills in high stakes games and tournaments and play for millions without any risk."); ?> </p>
	<p class="register-description"><?php print t("The better you play, the greater your chances for a date with a Playmate."); ?></p>
	<a id="register-now-chips" class="open-register-form" rel="nofollow"><span><?php print t("$1.000 Start bonus"); ?></span></a>
	<ul id="explanations">
		<li><a href="<?php print url("poker/help", array('query' => 'height=442&width=603&keepThis=true&TB_iframe=true&', 'fragment' => 'help-tutorial')); ?>" class="thickbox open-video-tutorial"><?php print t("Poker Tutorial"); ?></a></li>
	    <li><a href="?height=<?php print ($tutorial['size'][1]+10)?>&width=<?php print ($tutorial['size'][0]+5)?>&inlineId=poker-tutorial&#TB_inline" class="thickbox"><?php print t("This is how it works"); ?></a></li>
	</ul>

	<p><small class="legal">* <?php print t("You play in all games with virtual game money with no value."); ?></small></p>

    <div id="poker-tutorial">
        <div class="tb-fix-margin">
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
<a class="banner open-register-form" id="banner-join" title="<?php print t("Join the world&#039;s sexiest poker!"); ?>"><span class="banner-inner">Join the world's <strong>sexiest poker!</strong></span></a>
</div>

