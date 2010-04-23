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
<div id="profile-update">
	<div class="header">
		<div class="left fleft">
			<?php
				if ($target_user)
				{
					print "<div class=\"profile_user" . (($target_user->Online()) ? (" online") : ("")) . "\">" . $target_user->profile_nickname . "</div>";
				}
			?>
		</div>
		<div class="right fleft" style="display: none;">
			<?php
				if ($target_user)
				{
					print t("Player since") . ": <span class='darkred'>" . date("d.m.Y", $target_user->created) . "&nbsp;&nbsp;|&nbsp;&nbsp;</span>" . t("Chips") . ": <span class='darkred'>" . $target_user->Chips(TRUE) . "&nbsp;&nbsp;|&nbsp;&nbsp;</span>" . t("Status") . ": <span class='darkred'>" . $target_user->Status() . "</span>";

				}
			?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="middle">
		<div class="left fleft">
			<div class="picture">
			<?php
				if ($target_user)
				{
					print theme('user_picture', $target_user);
				}
			?>
			</div>
			<div class="user_stats">
   <?php   print "<p><strong>".t("Status:")."</strong> ".$target_user->Status()."</p>"; ?>
			<?php   print "<p><strong>".t("Profile:")."</strong> ".$target_user->GetProfileCompletePercent()."% " . t("filled!") . "</p>"; ?>
			 </div>
			 <div class="image_promo">
			 <?php
global $language;
				 print '<p class="Dollar">';
				 print t("For a complete Profile you'll get $2.000 Chips for free!");
				 print '</p>';
			?>
			</div>
		</div>
		<div class="right fleft">
			<div class="block_title_bar block_title_text"><?php print t("For a complete Profile you'll get $2.000 Chips for free!"); ?></div>
			<?php
				if ($form)
				{
					print $form;
				}
			?>
		</div>
		<div class="clear"></div>
	</div>
</div>
