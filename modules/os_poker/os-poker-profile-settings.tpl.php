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
<div id="profile-settings">
	<div id="personal-settings" class="setting-block">
		<div class="block_title_bar block_title_text"><?php print t("Personal Settings"); ?></div>
		<div class="dotted-red-border setting-subblock">
			<?php
				if ($personal_form)
				{
					print $personal_form;
				}
			?>
			<div class="clear"></div>
		</div>
	</div>	
	<div id="email-settings"  class="setting-block">
		<div class="block_title_bar block_title_text"><?php print t("Change E-Mail"); ?></div>
		<div class="dotted-red-border setting-subblock">
			<?php
				if ($email_form)
				{
					print $email_form;
				}
			?>
			<div class="clear"></div>	
		</div>
	</div>	
	<div id="password-settings"  class="setting-block">
		<div class="block_title_bar block_title_text"><?php print t("Change Password"); ?></div>
		<div class="dotted-red-border setting-subblock">
			<?php
				if ($password_form)
				{
					print $password_form;
				}
			?>
			<div class="clear"></div>
		</div>
	</div>
</div>
