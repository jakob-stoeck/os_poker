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
