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
