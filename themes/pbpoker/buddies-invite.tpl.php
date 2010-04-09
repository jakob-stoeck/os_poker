<?php

	$dailyChips = 0;
	$nextDailyChips = 0;
	$nextBuddyStep = 0;

	if ($current_user)
	{
		$task = new CDailyChips();
		$invites = $current_user->Invites();
		$nInvites = count($invites["accepted"]);
		$dailyChips = $task->GetChipsAttributions($nInvites, $nextBuddyStep, $nextDailyChips);
	}

?>

<div id="buddies-invite">
   <div class="buddies-invite-left">
   <div class="DailyBonusStep02">
   <h4><?php print t("Send your friends !chipsamount Bonus chips!", array('!chipsamount' => '<strong> $5000')); ?> <em>*</em></strong></h4>
   <p id="invitethemnow"><?php print t("Invite now all your friends!"); ?></p>
		<p class="description"><?php print t("For logging in daily you get: !dailyChips Bonus chips!", array('!dailyChips' => "<strong>$$dailyChips" )); ?></strong></p>
		<div id="chipsoninvitemore">
			<h5><?php print t("Get !amount per day!", array('!amount' => "<strong>$$nextDailyChips</strong>")); ?></h5>
			<p><?php print t("Just invite !nb_buddies new pokerfriends!", array('!nb_buddies' => (max(($nextBuddyStep - $nInvites), 0)))); ?></p>
			<p><?php print t("The more friends you invite, the more free chips you get daily!"); ?></p>

		</div>
   </div>

   <p class="Link"><?php print l(t("See present invitations"), "<front>", array("query" => "q=poker/buddies/invitedlist")); ?><br/>
   <a href="javascript:void(0);" onclick="javascript:parent.tb_remove();"><?php print t("Continue to play"); ?></a>
   </p>

   <p class="Note">
   <?php print t("* The bonus applies only to successfully invited new friends."); ?>
   </p>

   </div>

   <div class="buddies-invite-right">
	<?php
		print($form);
   ?>
   </div>

</div>
