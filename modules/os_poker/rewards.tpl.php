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
	?>
	</div>
	<div id="profile-rewards">
	<?php
		print theme('os_poker_reward_fulllist', $rewards);
	?>
	</div>
</div>