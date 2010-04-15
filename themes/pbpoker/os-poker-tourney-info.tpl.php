				<div id="tourney-teaser" class="main-teaser">
					<?php if (!user_is_logged_in()): ?>
					<h2><a href="<?php print url("", array('fragment' => 'registration-window')); ?>"><?php print t('Register now'); ?></a></h2>
					<?php endif; ?>
				</div>
				<div id="middle-content">
					<div id="middle-content-left">
						<div class="infobox" id="poker-masters">
							<h3>Playboy Poker Masters</h3>
							<h4>Playboy Poker Masters</h4>
							<p>Das grosse Playboypoker Turnier um wechselnde Sachpreise.<br />
							   Dieses Turnier hat kein Buy-in und findet alle 2 Monate zu festgelegten Terminen statt.</p>
						</div>
						<div class="infobox" id="bunny-hunter">
							<h3>Bunny Hunter</h3>
							<h4>Bunny Hunter</h4>
							<p>Die Hasen-Jagd-Saison ist eröffnet!<br />
							Die Besten teilen sich den Preispool aber wer dem Bunny den letzten Chip aus der Tasche zieht, kann den Bunny Hunter Special Preis als Trophäe mit nach Hause nehmen.</p>
						</div>
						<div class="infobox" id="millions">
							<h3>Monthly Millions</h3>
							<h4>Monthly Millions</h4>
							<p>Jeden ersten Sonntag im Monat ein Preispool mit 1 Million Chips,
Nervenkitzel garantiert! Los geht's um 19:00 Uhr.</p>
						</div>
						<div class="infobox" id="after-work">
							<h3>After Work Poker</h3>
							<h4>After Work Poker</h4>
							<p>Spät ins Bett gehen nur weil man ein Pokerturnier spielen will, muss unter der Woche niemand mehr. Auf Playboy Poker jeden Donnerstag um 18:30 Uhr ganz entspannt Afterwork Poker spielen!</p>
						</div>
						<div class="infobox" id="gentlemen">
							<h3>Gentleman's Pokerclub</h3>
							<h4>Gentleman's Pokerclub</h4>
							<p>Hier trennt sich die Spreu vom Weizen, denn wer kann sich schon ein Buy-in von 10.000 Chips leisten? Die Turniere des Gentleman‘s Pokerclub finden alle 2 Wochen Sonntags um 19:00 Uhr statt. </p>
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
							<a href="poker/pages/promotion_and_events"); ?></a>
						</div>
						</div>
				    </div>
				  </div>
