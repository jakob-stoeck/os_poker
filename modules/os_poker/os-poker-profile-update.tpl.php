<div id="profile-update">
	<div class="header">
		<div class="left fleft">
			<?php
				if ($target_user)
				{
					print "<div class=\"profile_user" . (($target_user->Online()) ? (" online") : ("")) . "\">" . $target_user->profile_nickname . "</div>";
				}
			?>
		</div>
		<div class="right fleft" style="display: none;">
			<?php
				if ($target_user)
				{
					print t("Player since") . ": <span class='darkred'>" . date("d.m.Y", $target_user->created) . "&nbsp;&nbsp;|&nbsp;&nbsp;</span>" . t("Chips") . ": <span class='darkred'>" . $target_user->Chips(TRUE) . "&nbsp;&nbsp;|&nbsp;&nbsp;</span>" . t("Status") . ": <span class='darkred'>" . $target_user->Status() . "</span>";
					
				}
			?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="middle">
		<div class="left fleft">
			<div class="picture">
			<?php
				if ($target_user)
				{
					print theme('user_picture', $target_user);
				}
			?>
			</div>
			<div class="user_stats">
   <?php   print "<p><strong>".t("Status:")."</strong> ".$target_user->Status()."</p>"; ?>
			<?php   print "<p><strong>".t("Profile:")."</strong> ".$target_user->GetProfileCompletePercent()."% " . t("filled!") . "</p>"; ?>
			 </div>
			 <div class="image_promo">
			 <?php
global $language;
				 print '<p class="Dollar">2.000</p>';
				 print '<img src="' . drupal_get_path("theme", "poker") . "/images/bg_update_profile_" . $language->language . ".gif" . '" alt="promo"/>';
			?>
			</div>
		</div>
		<div class="right fleft">
			<div class="block_title_bar block_title_text"><?php print t("For a complete Profile you'll get $2.000 Chips for free!"); ?></div>
			<?php
				if ($form)
				{
					print $form;
				}
			?>
		</div>
		<div class="clear"></div>
	</div>
</div>