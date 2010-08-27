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
