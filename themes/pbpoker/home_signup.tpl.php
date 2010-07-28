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
<div id="home_signup">
	<div class="title">
		<?php print t("SIGN UP : It's free and anyone can join !"); ?>
	</div>
	<div id="home_signup_form">
		<?php if(fb_canvas_is_iframe()): // no standard registration on facebook ?>
			<p style="margin:20px 0 0 160px"><fb:login-button perms="email,user_birthday,user_hometown,user_location" onclick="FB_Connect.login_onclick()" v="2"><fb:intl>Connect with Facebook</fb:intl></fb:login-button></p>
		<?php else: ?>
		<?php print $form; ?>
		<?php endif; ?>
	</div>
	<div id="home_signup_teaser">
		<h4><?php print t("Play poker with your friends and our bunnys!"); ?></h4>
		<p><?php print t("Sex is like a poker game. If you do not have a good partner, you at least have to have a damn good hand."); ?></p>
	</div>
</div>