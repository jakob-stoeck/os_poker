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
	   <p class="Dollar1"><?php print 5000; ?></p>
	   <p class="Dollar2"><?php print $dailyChips; ?></p>
	   <p class="Dollar3"><?php print $nextDailyChips; ?></p>
	   <p class="Buddies"><?php print ($nextBuddyStep - $nInvites); ?></p>
   </div>

   <p class="Link"><?php print l(t("See present invitations"), "<front>", array("query" => "q=poker/buddies/invitedlist")); ?><br/>
   <a href="javascript:void(0);" onclick="javascript:parent.tb_remove();"><?php print t("Continue to play"); ?></a>
   </p>

   <p class="Note">
   <?php print t("* Please note that just new player are getting this bonus."); ?>
   </p>

   </div>

   <div class="buddies-invite-right">
	<?php
		print($form);
   ?>
   </div>

</div>