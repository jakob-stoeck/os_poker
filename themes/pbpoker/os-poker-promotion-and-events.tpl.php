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
