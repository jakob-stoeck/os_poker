<div id="outter-poker-first-profile">&nbsp;
</div>

<div id="poker-first-profile">
  <a class="LayerClose" onclick="javascript:os_poker_submit(this, 'os-poker-first-profile-form');" href="javascript:void(0);">&nbsp;</a>
	<div class="title">
		<h1><?php print $title ?></h1> <?php print $subtitle ?>
	</div>
	<div class="body">
		<?php print $form; ?>
	</div>
	<div class="find_new_buddies">
		<p>
			<strong><?php print $find_new_buddies ?></strong>
		</p>
		<div class="userlist">
			<?php print $avatars ?>
		</div>   
	</div>
  <div class="footer">
		<?php print $footer ?>
	</div>
</div>

<script type="text/javascript">
   $("#messages").show();
   $(".error").show();
   $(".messages").addClass("first_profile_message");
</script>
