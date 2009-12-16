<div id="ranking">
	<div class="header">
		<?php
			if ($target_user)
			{
				print "<div class=\"profile_user" . (($target_user->Online()) ? (" online") : ("")) . "\">" . $target_user->profile_nickname . "</div>";
			}
		?>
	</div>
	<div class="panel">
		<div class="left fleft">
			<div class="picture">
			<?php
				if ($target_user)
				{
					print '<img src="' . $target_user->picture . '" alt="user"/>';
				}
			?>
			</div>
			<div class="block_title_bar block_title_text"><?php print t("Rank") . ": " . $user_rank; ?></div>
		</div>
		<div class="right fleft">
			<div class="block_title_bar block_title_text"><?php print t("Top Ten"); ?></div>
			<div id="profile-ranking">
				<?php print theme('os_poker_ranking_list', $sorted_users); ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>