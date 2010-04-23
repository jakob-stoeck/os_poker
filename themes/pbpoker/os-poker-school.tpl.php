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
				<div id="school-teaser" class="main-teaser">
					<?php if (!user_is_logged_in()): ?>
					<h2><a href="<?php print url("", array('fragment' => 'registration-window')); ?>"><?php print t('Register now'); ?></a></h2>
					<?php endif; ?>
				</div>
				<div id="middle-content">
					<div id="middle-content-left">
						<div class="infobox" id="cards">
							<h3>Pokerblätter</h3>
							<p>Vom Paar bis zum Royal Flush, gewinnen kann man mit jedem Blatt. Um zu wissen, wie gut deine Karten wirklich sind, musst du nur 9 Pokerblätter kennen. Klicke auf "weiter" um mehr kennenzulernen.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("weiter", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-pokerhands')); ?></div></div>
						</div>
						<div class="infobox" id="rules">
							<h3>Hold'Em Regeln</h3>
							<p>Die Pokerregeln sind schnell gelernt und Spass macht es von Anfang an, da auch der absolute Anfänger gegen einen Profi gewinnen kann. Spannend bleibt es aber immer, da man beim Poker ständig durch Erfahrung sein Spiel verbessert.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("weiter", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-rules')); ?></div></div>
						</div>
						<div class="infobox" id="tips">
							<h3>Tips</h3>
							<p><strong>Spiele ausgewählte Hände.</strong><br /> Geduld führt zu erfolgreichem Spiel. Poker-Profis schauen sich nur in 20-30% der Fälle den Flop an. Es lohnt sich, auf gute Starthände zu warten, um den Glücksfaktor zu minimieren.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("weiter", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-tips')); ?></div></div>
						</div>
						<div class="infobox" id="guidelines">
							<h3>Richtlininien</h3>
							<p>Fairness am Spieltisch und gegenseitiger Respekt sind unsere wichtigsten Grundsätze. Um den Spass am Spiel zu gewährleisten, bitten wir euch, einige Regeln zu beachten...</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("weiter", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-guidelines')); ?></div></div>
						</div>
					</div>
					<div id="middle-content-right">
						<div class="infobox" id="video-box">
							<h3>Noch nie gepokert?</h3>
							<p id="gototuto">Hier geht's zum Videotutorial mit Bunny Michaela.</p>
							<div class="poker_submit"><div class="pre"></div><div class="label"><?php print l("Tutorial", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-tutorial')); ?></div></div>
						</div>
						<div class="infobox" id="quicklinks">
							<h3>Quicklinks</h3>
							<ul>
                            	<li>» <?php print l("Tutorial", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-tutorial')); ?></li>
								<li>» <?php print l("Hold'em Regeln", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-rules')); ?></li>
								<li>» <?php print l("Pokerblätter", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-pokerhands')); ?></li>
								<li>» <?php print l("Tipps", "poker/help/", array('attributes' => array('class' => 'thickbox'), 'query' => 'height=442&width=603&keepThis=true&TB_iframe=true', 'fragment' => 'help-tips')); ?></li>
							</ul>
						</div>
						<div class="teaser" id="tourneyteaser">
							<?php print l("Zu den Turnieren", "poker/pages/tourneyinfo"); ?>
						</div>
					</div>
				</div>
