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
					</div>
					<div id="middle-content-right">
					</div>
				</div>
