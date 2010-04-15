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
						</div>
						<div class="infobox" id="rules">
							<h3>Hold'Em Regeln</h3>
							<p></p>
						</div>
						<div class="infobox" id="tips">
							<h3>Tips</h3>
							<p></p>
						</div>
						<div class="infobox" id="guidelines">
							<h3>Richtlininien</h3>
							<p></p>
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
						<div class="teaser" id="tourneys">
						</div>
					</div>
				</div>
