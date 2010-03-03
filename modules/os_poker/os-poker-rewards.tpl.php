<div id="rewards">
	<div class="header">
	<?php
		$rewards = NULL;
	
		if ($target_user)
		{
			$rewards = $target_user->Rewards();
			$nRewards = 0;
			$nUserRewards = 0;
			
			foreach ($rewards as $name => $value)
			{
				++$nRewards;
				if ($value["value"] != 0)
					++$nUserRewards;
			}
		
			print "<div class=\"profile_user" . (($target_user->Online()) ? (" online") : ("")) . "\">" . $target_user->profile_nickname . " ({$nUserRewards} / {$nRewards})</div>";
		}
		print ("<div class=\"rewards_help_div\"><span class=\"rewards_help_link\"><a href=\"?q=poker/help/&height=442&width=603&keepThis=true&TB_iframe=true\" alt=\"What are rewards?\">What are rewards?</a></span></div>");
	?>
	</div>
	<div id="profile-rewards">
	<?php
		print theme('os_poker_reward_fulllist', $rewards);
	?>
	</div>
</div>