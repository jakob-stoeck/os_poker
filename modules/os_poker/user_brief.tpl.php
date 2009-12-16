<div id="user-brief">
	<div id="user-brief-inner">
		<div class="fleft avatar">
			<img src="<?php print $os_user->picture; ?>" alt="avatar"/>
		</div>
		<div class="fleft info1">
			<p class="welcome">
				<?php print t("Welcome") . ",<br/>" . ucfirst($os_user->profile_nickname); ?>
			</p>
			<p class="info">
				<?php 
					print t("Chips") . ": <b class='chips'>" . $os_user->Chips(TRUE) . "</b><br/>";
					
					$s = $os_user->Status($level, $maxl);
					
					print t("Status") . ": {$s} ({$level}/{$maxl})";
				?>
			</p>
		</div>

		<?php 
			if (($num_rewards = $os_user->GetNumRewards())) 
			{
				$rewards = $os_user->Rewards();
				$reward_key = $os_user->GetLastReward();
				$reward_last = $rewards[$reward_key];
		?>
	
			<div class="fleft reward_separator">
				<img src="<?php print drupal_get_path("theme", "poker") . "/images/brief_separator.png"; ?>" alt="avatar"/>
			</div>
			<div class="fleft">
			    <p class="reward_title"><strong>
					<?php print t("Your rewards") . ":"; ?>
			    </strong></p>
			    <div class="reward_icon">
			      <?php print("<img class='reward_picture' src=".$reward_last['picture']." alt='reward' />"); ?>
			    </div>
			    <div class="reward_status"><p><strong>
			      <?php print($reward_last['name']); ?>
			    </strong>
			    <br/>
			      <?php print($num_rewards." / ".count($rewards)); ?>
			    </p></div>
			</div>
			
		<?php } ?>
		
		<div class="clear"></div>
	</div>
</div>