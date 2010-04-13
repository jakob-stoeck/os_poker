				<div id="tourney-teaser">
					<?php if (!user_is_logged_in()): ?>
					<h2><a href="<?php print url("", array('fragment' => 'registration-window')); ?>"><?php print t('Register now'); ?></a></h2>
					<?php endif; ?>
				</div>
				<div id="middle-content">
					<div id="middle-content-left">
						<div class="tourney-infobox" id="poker-masters">
							<h3>Playboy Poker Masters</h3>
							<h4>Playboy Poker Masters</h4>
							<p>Das grosse Playboypoker Turnier um wechselnde Sachpreise.<br />
							   Dieses Turnier hat kein Buy-in und findet alle 2 Monate zu festgelegten Terminen statt.</p>
						</div>
						<div class="tourney-infobox" id="bunny-hunter">
							<h3>Bunny Hunter</h3>
							<h4>Bunny Hunter</h4>
							<p>Die Hasen-Jagd-Saison ist eröffnet!<br />
							Die Besten teilen sich den Preispool aber wer dem Bunny den letzten Chip aus der Tasche zieht, kann den Bunny Hunter Special Preis als Trophäe mit nach Hause nehmen.</p>
						</div>
						<div class="tourney-infobox" id="millions">
							<h3>Monthly Millions</h3>
							<h4>Monthly Millions</h4>
							<p>Jeden ersten Sonntag im Monat ein Preispool mit 1 Million Chips,
Nervenkitzel garantiert! Los geht's um 19:00 Uhr.</p>
						</div>
						<div class="tourney-infobox" id="after-work">
							<h3>After Work Poker</h3>
							<h4>After Work Poker</h4>
							<p>Spät ins Bett gehen nur weil man ein Pokerturnier spielen will, muss unter der Woche niemand mehr. Auf Playboy Poker jeden Donnerstag um 18:30 Uhr ganz entspannt Afterwork Poker spielen!</p>
						</div>
						<div class="tourney-infobox" id="gentlemen">
							<h3>Gentleman's Pokerclub</h3>
							<h4>Gentleman's Pokerclub</h4>
							<p>Hier trennt sich die Spreu vom Weizen, denn wer kann sich schon ein Buy-in von 10.000 Chips leisten? Die Turniere des Gentleman‘s Pokerclub finden alle 2 Wochen Sonntags um 19:00 Uhr statt. </p>
						</div>
					</div>

              <a class="thickbox" href="<?php print url("poker/help", array('query' => 'height=442&width=603&keepThis=true&TB_iframe=true&', 'fragment' => 'help-tutorial')); ?>">
                Tutorial
              </a>
					<div id="middle-content-right">
						<div class="tourney-infobox" id="prize-box">
							<h3>Die Gewinne:</h3>
							<ul>
								<li>1. Preis: Besuch eines Playboy-Shootings</li>
								<li>2. Preis: Lebenslanges Playboy-Abo</li>
								<li>3. Preis: Lebenslanges CyberClub-Abo</li>
							</ul>
							<p>» zusätzlich 1 Million Chips Preispool!!!</p>
						</div>
						<div class="tourney-infobox" id="participation-box">
							<h3>Wie kann ich teilnehmen?</h3>
							<ul>
								<li>- Jetzt kostenlos anmelden und 1.000 Chips Startguthaben sichern<br />
								&nbsp;&nbsp; Nur noch Dein Profil ausfüllen und fertig! So einfach ist das...</li>
								<li>- Das Turnier findest Du in der <?php print l("Lobby", ""); ?> unter "Turniere".<br />
								&nbsp;&nbsp; Ab dem 02. März kannst Du dich dort für das Turnier registrieren.</li>
								<li>- Noch nie gepokert? <?php print l("Hier", "poker/help/&initcall_show_tutorial_tab=true&height=442&width=603&keepThis=true&TB_iframe=true", array('attributes' => array('class' => "thickbox"))); ?> geht's zum Video-Tutorial mit Bunny Michaela.</li>
							</ul>
						</div>
						<div class="tourney-infobox" id="date-box">
							<h3>Ohne Buy-in zum Millionär!</h3>
							<p><strong>Jeden ersten Sonntag im Monat ein Preispool mit 1 Million Chips, Nervenkitzel garantiert!</strong></p>
							<p>Nächste Termine: <br />
							07. März, 04. April, 02. Mai, 06. Juni, 04. Juli - Startzeit: 19:00 Uhr
							</p>
						</div>
						<div class="tourney-infobox" id="tos-box">
							<h3>Teilnahmebedingungen:</h3>
							<ul>
								<li>- Jeder Teilnehmer muss das 18. Lebensjahr vollendet haben.</li>
								<li>- Die Teilnahme ist kostenlos!</li>
								<li>- Die Preise sind nicht übertragbar oder in bar auszahlbar.</li>
								<li>- Bei sämtlichen Einsätzen handelt es sich um virtuelles Spielgeld<br />
								&nbsp;&nbsp; ohne Gegenwert.</li>
								<li>- Wir bitten um Beachtung unserer <?php print l("Nutzungsbedingungen", "poker/tos", array('attributes' => array('class' => 'thickbox'),
'query' => array("height" => 442, "width" => 603, 'keepThis' => TRUE, 'TB_iframe' => TRUE),)); ?>.</li>
							</ul>
						</div>
				    </div>
				  </div>
