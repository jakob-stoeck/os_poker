<div id="user_login">
		<p class="fleft"><?php print t("Welcome") . ", <strong>" . ucfirst($os_user->profile_nickname)."</strong>"; ?></p>
		<p class="fright"><? print l(t('Logout'), 'poker/logout', array('attributes'=> array('class' => 'logout'))); ?></p>
		<div class="clear"></div>
		<div id="avatar" class="fleft"><?php print theme('user_picture', $os_user); ?></div>

		<div id="user_info_box">
			<div class="fleft">
				<p class="buddy_count">
					<?php
						$buddies = $os_user->Buddies(TRUE);
						$status_string = t("You have @buddies Pokerbuddies", array("@buddies" => count($buddies))) . " (<u>";
						$onl = 0;
						foreach($buddies as $buddy) {
							if ($buddy->Online()) {
								++$onl;
							}
						}
						$status_string .= t("@buddies Online", array("@buddies" => $onl)) . "</u>)";
						print $status_string;
					?>
				</p>
			</div>

			<div class="fright">
				<div class="poker_submit" onclick='javascript:os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/buddies/invite", array("query" => array("height" => 442, "width" => 603), "absolute" => FALSE)); ?>", lightbox:true});'>
					<div class="label"><?php print(t("Invite more")) ?></div>
				</div>
			</div>

			<div class="fleft">
				<p class="chips_count">
					<?php print t("You have !chips Chips", array("!chips" => "<u><b class='chips'>" . $os_user->Chips(TRUE) . "</b></u>")); ?>
				</p>
			</div>
			<div class="fright">
				<div class="poker_submit" onclick='javascript:os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/shop/get_chips", array("query" => array("height" => 442, "width" => 603), "absolute" => FALSE)); ?>", lightbox:true});'>
					<div class="label" style="width: 60px; text-align: center;"><?php print(t("Get more")) ?></div>
				</div>
			</div>
		</div>
</div>
