<div id="profile-medium">
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="block_title_bar block_title_text"><?php if ($target_user) { print $target_user->profile_nickname; } ?></div>
	<div class="panel">
		<?php
			if ($target_user) {
		?>
		<div class="picture fleft">
			<img src="<?php print $target_user->picture; ?>" alt="user"/>
		</div>
		<div class="links fleft">
			<div onclick='javascript:parent.os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/profile/profile/" . $target_user->uid, array("query" => array("height"=>442, "width"=>603))); ?>", lightbox:true});' class="poker_submit">
				<div class="pre">&nbsp;</div>
				<div class="label"><?php print t("Profile"); ?></div>
				<div class="user_login_clear"></div>
			</div>
			<div onclick='javascript:parent.os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/shop/shop/1/buddy/" . $target_user->uid, array("query" => array("height"=>442, "width"=>603))); ?>", lightbox:true});' class="poker_submit">
				<div class="pre">&nbsp;</div>
				<div class="label"><?php print t("Buy Gift"); ?></div>
				<div class="user_login_clear"></div>
			</div>
			<?php if ($external == TRUE && !$target_user->profile_ignore_buddy) {

				$buddies = $current_user->Buddies();
				if (!in_array($target_user->uid, $buddies) && !$current_user->BuddyRequested($target_user->uid)) {
			?>
			<div id="user_relationships_popup_form" class="user_relationships_ui_popup_form" style="width:282px;"></div>
			<div class="poker_submit">
				<div class="pre">&nbsp;</div>
				<div class="label">
				<a class="user_relationships_popup_link" href="<?php print url("relationship/" . $target_user->uid . "/request/" . $current_user->uid, array("query" => array("destination" => "poker/profile/medium/" . $target_user->uid))); ?>">
					<?php print t("Add Buddy"); ?>
				</a>
				</div>
				<div class="user_login_clear"></div>
			</div>

			<?php } } ?>
		</div>
		<div class="info fleft">
			<?php print t("Status"); ?> : <span class='darkred'><?php print $target_user->Status(); ?></span><br/>
			<?php print t("Chips"); ?> : <span class='darkred'><?php print $target_user->Chips(TRUE); ?></span><br/>
			<span><?php print $target_user->profile_city; ?></span><br/>
			<span><?php print $target_user->profile_country; ?></span><br/>

		</div>
		<div class="items fleft">
			<div class="block_title_bar block_title_text"><?php print (($external == TRUE) ? t("Today's Gift") : t("Activate Item")); ?></div>
			<div id="item_panel">
				<a href="javascript:void(0);" class="previous">&nbsp;</a>
				<div class="list">
					<div class="cursor">
						<?php
							$inventory = $target_user->Items();

							foreach ($inventory as $item)
							{
								print '<img id="' . $item->id_operation . '" ' . (($item->active == 1) ? ("class='active'") : ("")) . ' title="' . t($item->item->name) . '" src="' . $item->item->picture . '" alt="' . t($item->item->name) . '"/>';
							}
						?>
					</div>
				</div>
				<a href="javascript:void(0);" class="next">&nbsp;</a>
				<div class="clear"></div>
			</div>
		</div>
		<div class="abuse fleft">
			<?php if ($external == TRUE) { ?>
			<div onclick='javascript:void(0);' class="poker_submit">
				<div class="pre">&nbsp;</div>
				<div class="label"><?php print t("Report Abuse"); ?></div>
				<div class="user_login_clear"></div>
			</div>
			<?php } ?>
		</div>
		<div class="activate fleft">
			<?php if ($external == FALSE) { ?>
			<div onclick='javascript:os_poker_activate_item(os_poker_slider_get_page($("#item_panel"), 50));' class="poker_submit">
				<div class="pre">&nbsp;</div>
				<div class="label"><?php print t("Activate"); ?></div>
				<div class="user_login_clear"></div>
			</div>
			<?php } ?>
		</div>

		<?php } ?>
		<div class="clear"></div>
	</div>
</div>
