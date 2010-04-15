				<div id="tourney-teaser" class="main-teaser">
					<?php if (!user_is_logged_in()): ?>
					<h2><a href="<?php print url("", array('fragment' => 'registration-window')); ?>"><?php print t('Register now'); ?></a></h2>
					<?php endif; ?>
				</div>
				<div id="middle-content">
					<div id="middle-content-left">
						<div class="infobox" id="poker-masters">
							<h3>Playboy Poker Masters</h3>
						    <p>Die Turniere der <strong>Playboy Poker Masters</strong> darf man sich einfach nicht entgehen lassen: Exklusive Preise von Playboy für die Gewinner, besser kann man die Zeit am Sonntagabend kaum nutzen!</p>
							<p>Die Playboy Poker Masters finden alle 2 Monate statt.<br />
							<strong>Nächster Termin: Sonntag, 25. April um 19:00 Uhr</strong></p>
						</div>
						<div class="infobox" id="bunny-hunter">
							<h3>Bunny Hunter</h3>
							<p><strong>Die Hasen-Jagd-Saison ist eröffnet</strong></p>
							<p>Die Besten teilen sich den Preispool aber wer dem Bunny den letzten Chip aus der Tasche zieht, kann den Bunny Hunter Special Preis als Trophäe mit nach Hause nehmen.</p>
							<p><strong>Jeden dritten Sonntag im Monat um 19:00 Uhr.</strong></p>
						</div>
						<div class="infobox" id="millions">
							<h3>Monthly Millions</h3>
							<p><strong>Monthly Millions</strong></p>
							<p>Der Sonntag ist auf Playboy Poker garantiert kein Ruhetag!</p>
							<p>Jeden ersten Sonntag im Monat ein Preispool mit <strong>1 Million Chips</strong>, Nervenkitzel garantiert bei den <strong>Monthly Millions</strong>!</p>
							<p>Los geht's immer <strong>um 19:00 Uhr.</strong></p>
						</div>
						<div class="infobox" id="after-work">
							<h3>After Work Poker</h3>
							<p><strong>After Work Poker</strong></p>
							<p>Spät ins Bett gehen nur weil man ein Pokerturnier spielen will, muss unter der Woche niemand mehr.</p>
							<p>Auf Playboy Poker <strong>jeden Donnerstag um 18:30 Uhr</strong> ganz entspannt <strong>Afterwork Poker</strong> spielen!</p>
						</div>
						<div class="infobox" id="gentlemen">
							<h3>Gentleman's Pokerclub</h3>
							<p><strong>Gentleman's Pokerclub</strong></p>
							<p>Hier trennt sich die Spreu vom Weizen, denn wer kann sich schon ein Buy-in von 10.000 Chips leisten?</p>
							<p>Die Turniere des Gentleman‘s Pokerclub finden <strong>alle 2 Wochen Sonntags um 19:00 Uhr statt</strong>. </p>
						</div>
					</div>
					<div id="middle-content-right">
						<div class="infobox" id="participation-box">
							<h3>Wie kann ich teilnehmen?</h3>
							<p>Jetzt kostenlos anmelden und <strong>$1.000 Chips</strong> Startguthaben sichern. Nur noch Dein Profil ausfüllen und fertig! So einfach ist das...<br/>Die Turniere findest du in der Lobby unter "Turniere", hier kannst du dich registrieren.</p>
							<?php if (!user_is_logged_in()): ?>
								<div class="poker_submit"><div class="pre"></div><div class="label"><a href="<?php print url("", array('fragment' => 'registration-window')); ?>"><?php print t('Register now'); ?></a></div></div>
							<?php endif; ?>
						</div>
						<div class="infobox" id="video-box">
							<h3>Noch nie gepokert?</h3>
							<p id="gototuto">Hier geht's zum Videotutorial mit Bunny Michaela.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("zum Tutorial", "poker/help/&initcall_show_tutorial_tab=true&height=442&width=603&keepThis=true&TB_iframe=true"); ?></div></div>
						</div>
						<div class="infobox" id="promos-and-events">
							<?php print l("Zu den Promotions", "poker/pages/promotion_and_events"); ?>
						</div>
						</div>
				    </div>
				  </div>
