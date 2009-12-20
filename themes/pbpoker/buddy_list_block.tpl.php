<div class="buddy_list_block <?php if ($buddy && $buddy->Online()) { print "online"; } ?>">

	<div class="buddy_list_number <?php if ($buddy && $buddyNumber == 1) print "golden"; ?>">
		<?php print $buddyNumber; ?>
	</div>
	
	<?php if ($buddy) { ?>

		<div class="buddy_list_name <?php if ($buddy->Online()) { print "online"; } ?>">
			<?php 
				$oBuddy->name = $buddy->profile_nickname;
				$oBuddy->uid =  $buddy->uid;
				
				print theme('username', $oBuddy);
			?>
		</div>
		<div class="buddy_list_picture">
			<?php
			if (variable_get('user_relationships_show_user_pictures', 0))
			{
				$alt = t("@user's picture", array('@user' => $buddy->name ? $buddy->name : variable_get('anonymous', t('Anonymous'))));
		  
				$picture = theme('image', $buddy->picture, $alt, $alt, '', FALSE);
				$attributes = array('attributes' => array('title' => t('View user profile.'), "class" => "thickbox"),
									"query" => array("height" => 442,
													"width" => 603,
													"TB_iframe" => "true"),
									'html' => TRUE);
				print l($picture, "poker/profile/profile/$oBuddy->uid", $attributes);
			}
			?>
		</div>
		<div class="buddy_list_chips">
			<?php print $buddy->Chips(TRUE); ?>
		</div>
		<?php if (!$hide_links) { ?>
		<div class="buddy_list_links">
			<a href="javascript:void(0);" onclick="javascript:os_poker_send_message({type :'os_poker_challenge_user', challengetarget: <?php print $buddy->uid; ?>});"><?php print t("Challenge"); ?>&nbsp;&gt;&gt;</a><br/>
			<?php
			
				if ($buddy->Online())
				{
					$text = t("Join table");
					$url = "os_poker_send_message({type :'os_poker_invite_user', target: " . $buddy->uid . ", online: true})";
				}
				else
				{
					$text = t("Invite now");
					$url = "os_poker_send_message({type :'os_poker_invite_user', target: " . $buddy->uid . "})";
				}
			?>
			<a href="javascript:void(0);" onclick="javascript:<?php print $url; ?>;"><?php print $text; ?>&nbsp;&gt;&gt;</a><br/>
			<?php
				if ($buddy->profile_accept_gifts == 0)
				{
					print l(t("Send Gifts") . "&nbsp;&gt;&gt;", "poker/shop/shop/1/buddy/" . $buddy->uid, array("query" => array("height" => 442,
																																 "width" => 603,
																																 "TB_iframe" => "true"),
																												"attributes" => array("class" => "thickbox"),					
																												"html" => TRUE));
				}
			?>
		</div>
		<?php } ?>
	<?php } else { ?>
		<div class="buddy_list_placeholder">
			<div class="title">
				<?php print t("Invite"); ?>
			</div>
			<div class="body">
				<?php print l(t("Invite Friends"), "poker/buddies/invite", array("query" => array(	"height" => 442,
																									"width" => 603,
																									"TB_iframe" => "true"),
																				"attributes" => array("class" => "thickbox"))); ?>
			</div>
		</div>
	<?php } ?>
</div>
