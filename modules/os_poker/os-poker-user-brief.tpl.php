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
<div id="user-brief">
	<div id="user-brief-inner">
		<div class="fleft avatar">
      <?php print theme('user_picture', $os_user); ?>
		</div>
		<div class="fleft info1">
			<p class="welcome">
				<?php print t("Welcome") . ",<br/>" . ucfirst($os_user->profile_nickname); ?>
			</p>
			<p class="info">
				<?php
					print t("Chips") . ": <b class='chips'>" . $os_user->Chips(TRUE) . "</b><br/>";

					$s = $os_user->StatusEx($level, $maxl);

					print t("Status") . ": {$s} ({$level}/{$maxl})";
				?>
			</p>
		</div>

		<?php
													 //			if (($num_rewards = $os_user->GetNumRewards()))
			 if (1)
			{
			  $num_rewards = $os_user->GetNumRewards();
			  $rewards = $os_user->Rewards();
			  $reward_key = $os_user->GetLastReward();
			  $reward_last = $rewards[$reward_key];
			  $imagePath = '/'.file_directory_path() . "/poker_rewards/";
			  $defaultPicture = $imagePath ."reward_default.gif";

		?>

			<div class="fleft reward_separator">
				<img src="<?php print drupal_get_path("theme", "poker") . "/images/brief_separator.png"; ?>" alt="avatar"/>
			</div>
			<div class="fleft">
			    <p class="reward_title"><strong>
					<?php print t("Your rewards") . ":"; ?>
			    </strong></p>
			    <div class="reward_icon">
			     <?php if (!empty($reward_last['picture'])) { ?>
			      <?php print("<img class='reward_picture' src=".$reward_last['picture']." alt='reward' />"); ?>
			     <?php } else {?>
			      <?php print("<img class='reward_picture' src=".$defaultPicture." alt='reward' />"); ?>
			     <?php } ?>
			    </div>
			    <div class="reward_status"><p><strong>
            <?php if($num_rewards) {
              print($reward_last['name']);
            }
            else {
              print t('no rewards achieved');
            }?>
			    </strong>
			    <br/>
			      <?php print($num_rewards." / ".count($rewards)); ?>
			    </p></div>
			</div>

		<?php } ?>

		<div class="clear"></div>
	</div>
</div>
