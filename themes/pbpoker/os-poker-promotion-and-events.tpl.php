<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
				<div id="events-teaser" class="main-teaser">
					<?php if (!user_is_logged_in()): ?>
					<h2><a href="<?php print url("", array('fragment' => 'registration-window')); ?>"><?php print t('Register now'); ?></a></h2>
					<?php endif; ?>
				</div>
				<div id="middle-content">
					<div id="middle-content-left">
						<div class="infobox" id="randy">
							<h3>Randy Dandy</h3>
						</div>
						<div class="infobox" id="party">
							<h3>Bunny für Deine Party</h3>
						</div>
						<div class="infobox" id="sitngochamp">
							<h3>Sit & Go's Champion</h3>
						</div>
						<div class="infobox" id="limo">
							<h3>Playboy Poker Stretchlimo</h3>
						</div>
					</div>
					<div id="middle-content-right">
						<div class="infobox" id="spender-box">
							<h3>Big Spender 2010</h3>
						</div>
						<div class="teaser" id="schoolteaser">
							<?php print l("Zur Pokerschule", "poker/pages/school"); ?>
						</div>
					</div>
				</div>
