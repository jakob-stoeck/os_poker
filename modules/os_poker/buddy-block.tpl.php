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
<div class="buddy_list_block <?php if ($buddy && $buddy->Online()) { print "online"; } ?> <? if ($buddyNumber == 1) print "first_buddy";  ?>">

	<div class="buddy_list_number <?php if ($buddy && $buddyNumber == 1) print "golden"; ?>">
		<span><?php print $buddyNumber; ?></span>
	</div>

	<div style="clear:both"></div>

	<?php if ($buddy) { ?>

		<div class="buddy_list_name <?php if ($buddy->Online()) { print "online"; } ?>">
			<?php
				$oBuddy->name = $buddy->profile_nickname;
				$oBuddy->uid =  $buddy->uid;

				print theme('username', $buddy);
			?>
		</div>
		<div class="buddy_list_picture">
			<?php
			if (variable_get('user_relationships_show_user_pictures', 0))
			{
				print $picture = theme('user_picture', $buddy);
			}
			?>
		</div>
		<div class="buddy_list_chips">
			<?php print $buddy->Chips(TRUE); ?>
		</div>
		<?php if (!$hide_links) { ?>
		<div class="buddy_list_links">
		      <?php
		      if ($buddy->Online())
			{
		      ?>
			<a href="javascript:void(0);" onclick="javascript:os_poker_send_message({type :'os_poker_challenge_user', challengetarget: <?php print $buddy->uid; ?>});"><?php print t("Challenge"); ?> »</a><br/>
		      <?php
			}
		      else
			{
			  print "<span class='nolink'>".t("Challenge")." »</span><br/>";
			}
		      ?>

			<?php

				if ($buddy->Tables())
				{
					print l(t('Join table') .' »', 'user/'. $buddy->uid .'/table', array('html' => TRUE));

				}
				else
				{
					$onclick = "os_poker_send_message({type :'os_poker_invite_user', target: " . $buddy->uid . "}); return false";
          print l(t('Invite now') .' »', '', array('html' => TRUE, 'absolute' => TRUE, 'attributes' => array('onclick' => $onclick)));
				}
			?>
			<br/>
			<?php
				if ($buddy->profile_accept_gifts == 0)
				{
					print l(t("Send Gifts") . " »", "poker/shop/shop/1/buddy/" . $buddy->uid, array("query" => array("height" => 442,
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
				<div class="buddy_list_invite">
				<?php print l(t("Invite Friends"), "poker/buddies/invite", array("query" => array(	"height" => 442,
																									"width" => 603,
																									"TB_iframe" => "true"),
																				"attributes" => array("class" => "thickbox"))); ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
