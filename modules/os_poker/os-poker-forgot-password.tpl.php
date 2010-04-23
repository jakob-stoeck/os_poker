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
<div id="forgot-password">
  <a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="header">
		<h1><?php print t("Trouble Accessing Your Account ?"); ?></h1>
		<p><?php print t("Forgot your password ? Enter your login email below and we will send you an email to change your password."); ?></p>
	</div>
	<div class="panel">
	<?php
		if ($form)
		{
			print($form);
		}
	?>
	</div>
	<div class="footer">
	<?php print t("If you have a different problem accessing your account, please see our !help.", array("!help" => l(t("Login Problems Help Page"), "poker/help", array("attributes" => array("class" => "yellow"))))); ?>
	</div>
</div>

<script type="text/javascript">
   $("#messages").show();
   $(".error").show();
   $(".messages").addClass("forgot_password_message");
</script>
