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
							<p><strong>Freunde einladen und dafür täglich Chips kassieren!</strong></p>
							<p>Unter allen Mitspielern, die mindestens 5 Freunde erfolgreich eingeladen haben, verlosen wir jeden Monat eine von dem aktuellen Cover-Girl signierte Ausgabe des Playboys.</p>
						</div>
						<div class="infobox" id="party">
							<h3>Playboy Club-Tour 2009 - Party, Spass und Playmates</h3>
							<p>Je besser du spielst, je höher du aufsteigst in der Poker-Hierarchie, desto grösser werden deine Chancen auf ein Treffen mit einem echten Playboy Bunny bei der Playboy Club-Tour 2009. </p>
							<p>Der Gewinner wird Anfang November ermittelt.</p>
						</div>
						<div class="infobox" id="sitngochamp">
							<h3>Sit & Go's Champion</h3>
							<p>Lade deine Freunde ein und spiele mit ihnen die <strong>Playboy Poker Mini Turniere</strong>. Unter allen Spielern, die den Tisch regelmässig vollmachen, verlosen wir Bunny Pokerkoffer.</p>
						</div>
						<div class="infobox" id="limo">
							<h3>Playboy Poker Stretchlimo</h3>
							<p><strong>Die goldene Playboy Poker Stretchlimo</strong><br />Hole als erster sämtliche Auszeichnungen und gewinne für ein Wochenende die goldene Playboy Poker Stretchlimousine.</p>
						</div>
					</div>
					<div id="middle-content-right">
						<div class="infobox" id="spender-box">
							<h3>Big Spender 2010</h3>
							<p><strong>Big Spender 2010</strong><br />Spendiere deinen Mitspielern Drinks, Zigarren, oder ein GoGo Girl. Wer wird der<br /><strong>Big Spender 2010</strong>?</p>
						</div>
						<div class="teaser" id="schoolteaser">
							<?php print l("Zur Pokerschule", "poker/pages/school"); ?>
						</div>
					</div>
				</div>
