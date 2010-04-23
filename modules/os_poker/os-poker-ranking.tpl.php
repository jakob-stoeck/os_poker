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
			<?php
				if ($target_user)
				{
					print theme('user_picture', $target_user);
				}
			?>
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
