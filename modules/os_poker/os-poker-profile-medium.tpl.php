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
<div id="profile-medium">
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="block_title_bar block_title_text"><?php if ($target_user) { print $target_user->profile_nickname; } ?></div>
	<div class="panel">
		<?php
			if ($target_user) {
		?>
		<div class="picture fleft">
			<?php print theme('user_picture', $target_user); ?>
		</div>
		<div class="links fleft">
			<div onclick='javascript:parent.os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/profile/profile/" . $target_user->uid, array("query" => array("height"=>442, "width"=>603))); ?>", lightbox:true});' class="poker_submit">
				<div class="pre">&nbsp;</div>
				<div class="label"><?php print t("Profile"); ?></div>
				<div class="user_login_clear"></div>
			</div>
			<?php if ($external == FALSE || !$target_user->profile_accept_gifts) : ?>
					<div onclick='javascript:parent.os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/shop/shop/1/table/" . $game_id . "/" . $target_user->uid, array("query" => array("height"=>442, "width"=>603))); ?>", lightbox:true});' class="poker_submit">
					<div class="pre">&nbsp;</div>
					<div class="label"><?php print t("Buy Gift"); ?></div>
					<div class="user_login_clear"></div>
				</div>
			<?php endif; ?>
			<?php if ($external == TRUE && !$target_user->profile_ignore_buddy) { 	
				$buddies = $current_user->Buddies();
				if (!in_array($target_user->uid, $buddies) && !$current_user->BuddyRequested($target_user->uid)) {
          print drupal_get_form('os_poker_add_buddy_button', $target_user->uid);
        }
      } ?>
		</div>
		<div class="info fleft">
			<?php print t("Status"); ?> : <span class='darkred'><?php print $target_user->Status(); ?></span><br/>
			<?php print t("Chips"); ?> : <span class='darkred'><?php print $target_user->Chips(TRUE); ?></span><br/>
			<span><?php print $target_user->profile_city; ?></span><br/>
			<span><?php print theme('country', $target_user->profile_country); ?></span><br/>
			
		</div>
		<div class="items fleft">
		<?php if ($external == FALSE) { ?>
			<div class="block_title_bar block_title_text"><?php print (($external == TRUE) ? t("Today's Gift") : t("Activate Item")); ?></div>
			<div id="item_panel" class="dotted-red-border">
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
			<?php } ?>
		</div>
		<div class="abuse fleft">
			<?php if ($external == TRUE) { ?>
      <div onclick='javascript:parent.os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/report_abuse/" . $target_user->uid, array('query' => array("height"=>352, "width"=>300))); ?>", lightbox:true});' class="poker_submit">
        <div class="pre">&nbsp;</div>
        <div class="label"><?php print t("Report Abuse"); ?></div>
        <div class="user_login_clear"></div>
      </div>
			<?php } ?>
		</div>
		<div class="activate fleft">
			<?php if ($external == FALSE) { ?>
			<div onclick='javascript:os_poker_activate_item(os_poker_slider_get_page($("#item_panel")));' class="poker_submit">
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
