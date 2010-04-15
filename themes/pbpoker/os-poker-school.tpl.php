<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
				<div id="school-teaser" class="main-teaser">
					<?php if (!user_is_logged_in()): ?>
					<h2><a href="<?php print url("", array('fragment' => 'registration-window')); ?>"><?php print t('Register now'); ?></a></h2>
					<?php endif; ?>
				</div>
				<div id="middle-content">
					<div id="middle-content-left">
						<div class="infobox" id="cards">
							<h3>Pokerblätter</h3>
							<p>Vom Paar bis zum Royal Flush, gewinnen kann man mit jedem Blatt. Um zu wissen, wie gut Deine Karten wirklich sind, musst du nur 9 Pokerblätter kennen. Klicke auf "weiter" um mehr kennenzulernen.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("weiter", "poker/help/?true&height=442&width=603&keepThis=true&TB_iframe=true"); ?></div></div>
						</div>
						<div class="infobox" id="rules">
							<h3>Hold'Em Regeln</h3>
							<p>Die Pokerregeln sind schnell gelernt und Spass macht es von Anfang an, da auch der absolute Anfänger gegen einen Profi gewinnen kann. Spannend bleibt es aber immer, da man beim Poker ständig durch Erfahrung sein Spiel verbessert.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("weiter", "poker/help/?true&height=442&width=603&keepThis=true&TB_iframe=true"); ?></div></div>
						</div>
						<div class="infobox" id="tips">
							<h3>Tips</h3>
							<p><strong>Spiele ausgewählte Hände.</strong><br /> Geduld führt zu erfolgreichem Spiel. Poker-Profis schauen sich nur in 20-30% der Fälle den Flop an. Es lohnt sich, auf gute Starthände zu warten, um den Glücksfaktor zu minimieren.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("weiter", "poker/help/?true&height=442&width=603&keepThis=true&TB_iframe=true"); ?></div></div>
						</div>
						<div class="infobox" id="guidelines">
							<h3>Richtlininien</h3>
							<p>Fairness am Spieltisch und gegenseitiger Respekt sind unsere wichtigsten Grundsätze. Um den Spass am Spiel zu gewährleisten, bitten wir Euch, einige Regeln zu beachten...</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("weiter", "poker/help/?true&height=442&width=603&keepThis=true&TB_iframe=true"); ?></div></div>
						</div>
					</div>
					<div id="middle-content-right">
						<div class="infobox" id="video-box">
							<h3>Noch nie gepokert?</h3>
							<p id="gototuto">Hier geht's zum Videotutorial mit Bunny Michaela.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("zum Tutorial", "poker/help/&initcall_show_tutorial_tab=true&height=442&width=603&keepThis=true&TB_iframe=true"); ?></div></div>
						</div>
						<div class="infobox" id="quicklinks">
							<h3>Quicklinks</h3>
							<ul>
								<li>» <a href="">Tutorial</a></li>
								<li>» <a href="">Hold'em Regeln</a></li>
								<li>» <a href="">Pokerblätter</a></li>
								<li>» <a href="">Tipps</a></li>
							</ul>
						</div>
						<div class="teaser" id="tourneyteaser">
							<?php print l("Zu den Turnieren", "poker/pages/tourneyinfo"); ?>
						</div>
					</div>
				</div>
