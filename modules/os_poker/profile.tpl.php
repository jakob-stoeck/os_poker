<div id="profile">
	<div class="header">
		<div class="left fleft">
			<?php
				if ($target_user)
				{
					print "<div class=\"profile_user" . (($target_user->Online()) ? (" online") : ("")) . "\">" . $target_user->profile_nickname . "</div>";
				}
			?>
		</div>
		<div class="right fleft">
			<?php
				if ($target_user)
				{
					print t("Player since") . ": <span class='darkred'>" . date("d.m.Y", $target_user->created) . "&nbsp;&nbsp;|&nbsp;&nbsp;</span>" . t("Status") . ": <span class='darkred'>" . $target_user->Status() . "&nbsp;&nbsp;|&nbsp;&nbsp;</span>" . t("Chips") . ": <span class='darkred'>" . $target_user->Chips(TRUE) . "</span>";
					
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
		</div>
		<div class="right fleft">
			<div>
				<table cellspacing="0" cellpadding="0" class="TableRanking">				
					<tbody>
					<tr class="BgGrey">
						<td class="Column01"><?php print t("City"); ?>:</td>
						<td class="Column02"><?php print $target_user->profile_city; ?></td>
						<td class="Column03"><?php print t("Highest Chip Level"); ?>:</td>
						<td class="Column04">NA</td>
					</tr>
					<tr>
						<td class="Column01"><?php print t("Country"); ?>:</td>
						<td class="Column02"><?php print theme('country', $target_user->profile_country) ?></td>
						<td class="Column03"><?php print t("Biggest Pot Won"); ?>:</td>
						<td class="Column04">
						<?php
							if (is_numeric($target_user->BiggestPotWon))
							{
								print _os_poker_format_chips($target_user->BiggestPotWon / 100);
							}
							else
							{
								print $target_user->BiggestPotWon;
							}
						?>
						</td>
					</tr>
					<tr class="BgGrey">
						<td class="Column01"><?php print t("Age"); ?>:</td>
						<td class="Column02">
							<?php 
								$dob = strtotime($target_user->profile_dob['day'] . "-" . $target_user->profile_dob['month'] . "-" . $target_user->profile_dob['year']);
								$age = (time() - $dob);
								print(date("Y",$age) - 1970);
							?>
						</td>
						<td class="Column03"><?php print t("Best Hand"); ?>:</td>
						<td class="Column04">
						<?php 
							if ($target_user->BestHand != $target_user->DefaultValue('BestHand'))
							{
								$besthand = explode(' ', $target_user->BestHand);
								foreach ($besthand as $card)
								{
									$head = $card[0];
									$color = $card[1];
									print "<span class='card color_{$color}'>{$head}<img title='{$card}' alt='{$card}' src='" . drupal_get_path("module", "os_poker") . "/images/{$color}.png'/></span>";
								}
							}
							else
							{
								print $target_user->BestHand; 
							}
						?>
						</td>
					</tr>
					<tr>
					<?php
					/* Handle the case where gender is either not set or set to Don't Display */
					$gender = $target_user->profile_gender;
					if ($gender != 'Male' && $gender != 'Female') {
   						$gender_t = '';
					} else {
   						$gender_t = t($gender);
					}
					?>
						<td class="Column01"><?php print t("Gender"); ?>:</td>
						<td class="Column02"><?php print $gender_t; ?></td>
						<td class="Column03"><?php print t("Hands Played/won"); ?>:</td>
						<td class="Column04"><?php print $target_user->HandsPlayed; ?> / <?php print $target_user->HandsWon; ?></td>
					</tr>	
					</tbody>
				</table>
			</div>

            <div class="right">
			  <?php if ($external == FALSE) { ?>
			  <div onclick='javascript:os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/profile/update"); ?>", lightbox:false});' class="poker_submit">
				<div class="pre">&nbsp;</div>
				<div class="label"><?php print t("Update Profile"); ?></div>
				<div class="user_login_clear"></div>
			  </div>
			  <?php } ?>

			  <?php if ($external == TRUE) { ?>
			  <div>
				<div onclick='javascript:os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/shop/shop/1/buddy/" . $target_user->uid); ?>", lightbox:false});' class="poker_submit fleft">
				  <div class="pre">&nbsp;</div>
				  <div class="label"><?php print t("Send Gifts"); ?></div>
				  <div class="user_login_clear"></div>
				</div>
				<div onclick='javascript:os_poker_send_message({type:"os_poker_challenge_user", challengetarget: <?php print $target_user->uid; ?>});' class="poker_submit fleft" style="margin-left:5px;">
				  <div class="pre">&nbsp;</div>
				  <div class="label"><?php print t("Challenge"); ?></div>
				  <div class="user_login_clear"></div>
				</div>
				<div onclick='javascript:os_poker_send_message({type:"os_poker_invite_user", target: <?php print $target_user->uid; ?>, table: true});' class="poker_submit fleft" style="margin-left:5px;">
				  <div class="pre">&nbsp;</div>
				  <div class="label"><?php print t("Join Table"); ?></div>
				  <div class="user_login_clear"></div>
				</div>
				<div onclick='javascript:parent.os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/report_abuse/" . $target_user->uid,  array('query' => array("height"=>442, "width"=>603))); ?>", lightbox:true});' class="poker_submit fleft" style="margin-left:5px;">
				  <div class="pre">&nbsp;</div>
				  <div class="label"><?php print t("Report Abuse"); ?></div>
				  <div class="user_login_clear"></div>
				</div>
				<div class="clear"></div>
			  </div>
			  <?php } ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<!-- middlebottom placeholder -->
	<div class="bottom">
		<div class="left fleft">
			<div class="block_title_bar block_title_text"><?php print t("Recent Rewards"); ?>:</div>
			<div id="profile-last-rewards" class="item-list">
				<?php
					print theme('os_poker_reward_minilist', $target_user);
				?>
			</div>
			<div class="block_title_bar block_title_text"><?php print t("Recent Gifts"); ?>:</div>
			<div id="profile-last-items" class="item-list">
				<?php
					print theme('os_poker_item_minilist', $target_user);
				?>
			</div>
		</div>
		<div class="right fleft">
			<div class="block_title_bar block_title_text"><?php print t("Tournaments"); ?>:</div>
			<div class="profile_tourney_info">
			<table cellspacing="0" cellpadding="0" class="TourneyInfo TableRanking">				
			  <tbody>
				<tr class="BgGrey">
				  <td colspan="3" class="bold"><?php print t("Next Tournament"); ?>:</td>
				</tr>

				<tr>
				  <td colspan="3"> 
					<?php
					   if ($next_tourney) {
					       print t("Player is registered at") . ": ";
					       print "<a target='_top' href='" . url("", array('query' => array('t' => time()), 'fragment' => "tourney_serial=" . $next_tourney->serial)) .
						         "'>" . $next_tourney->description_short . "</a>";
                       } else {
                           print t("Player is not registered");
                       }
                    ?>
				  </td>
				</tr>

				<tr class="BgGrey">
				  <td colspan="3">&nbsp;</td>
				</tr>

				<tr class="BgGrey">
				  <td colspan="3" class="bold"><?php print t("Best Tournament Results"); ?>:</td>
				</tr>

				<?php
				   if ($tourney_results):
 			    ?>
				<tr class="table_header">
				  <td width="25px"><?php print t("Rank"); ?></td>
				  <td width="103px"><?php print t("Tourney"); ?></td>
				  <td width="99px"><?php print t("Date"); ?></td>
				</tr>
				<?php
				   else:
				?>
				<tr class="BgGrey">
				  <td colspan="3"><?php print t("No results"); ?></td>
				</tr>
				<?php
				   endif;
				?>

				<?php
				   $grey = 1;
				   foreach ($tourney_results as $tourney_result) {
				   if ($grey) {
				       $tr_class = "BgGrey";
				   } else {
				       $tr_class = "";
				    }
				?>
				<tr class="<?php print($tr_class) ?>">
				  <td><?php print($tourney_result->rank) ?></td>
				  <td><?php print(os_poker_short_string($tourney_result->description_short, 20)); ?></td>
				  <td><?php print(os_poker_format_date($tourney_result->start_time)) ?></td>
				</tr>
				<?php
				   $grey = !$grey;
				   }
                ?>
			  </tbody>
			</table>
            </div>
		</div>
		<div class="right clear"></div>
	</div>
</div>
