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
							<p>Vom Paar bis zum Royal Flush, gewinnen kann man mit jedem Blatt. Um zu wissen, wie gut Deine Karten wirklich sind, musst du nur 9 Pokerblätter kennen. Klicke auf "weiter" um mehr kennenzulernen.</p>
						</div>
						<div class="infobox" id="party">
							<h3>Bunny für Deine Party</h3>
							<p>Die Pokerregeln sind schnell gelernt und Spass macht es von Anfang an, da auch der absolute Anfänger gegen einen Profi gewinnen kann. Spannend bleibt es aber immer, da man beim Poker ständig durch Erfahrung sein Spiel verbessert.</p>
						</div>
						<div class="infobox" id="sitngochamp">
							<h3>Sit & Go's Champion</h3>
							<p><strong>Spiele ausgewählte Hände.</strong><br /> Geduld führt zu erfolgreichem Spiel. Poker-Profis schauen sich nur in 20-30% der Fälle den Flop an. Es lohnt sich, auf gute Starthände zu warten, um den Glücksfaktor zu minimieren.</p>
						</div>
						<div class="infobox" id="limo">
							<h3>Playboy Poker Stretchlimo</h3>
							<p>Fairness am Spieltisch und gegenseitiger Respekt sind unsere wichtigsten Grundsätze. Um den Spass am Spiel zu gewährleisten, bitten wir Euch, einige Regeln zu beachten...</p>
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
