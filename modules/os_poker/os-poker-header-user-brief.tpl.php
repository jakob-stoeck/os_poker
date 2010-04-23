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
<div id="user_login">
	<div class="right_align">
		<div class="user_login_brief_row">
			<div class="poker_submit" onclick='javascript:os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/buddies/invite", array("query" => array("height" => 442, "width" => 603), "absolute" => FALSE)); ?>", lightbox:true});'>
				<div class='pre'>&nbsp;</div>
				<div class='label'><?php print(t("Invite more")) ?></div>
				<div class='user_login_clear'></div>
			</div>
		</div>
		<div class="user_login_brief_row">
			<p class="buddy_count">
				<?php
					$buddies = $os_user->Buddies(TRUE);

					$status_string = t("You have @buddies Pokerbuddies", array("@buddies" => count($buddies))) . " (<b><u>";

					$onl = 0;

					foreach($buddies as $buddy)
					{
						if ($buddy->Online())
						{
							++$onl;
						}
					}

					$status_string .= t("@buddies Online", array("@buddies" => $onl)) . "</u></b>)";

					print $status_string;
				?>
			</p>
		</div>
		<div class="user_login_clear"></div>
		<div class="user_login_brief_row">
			<div class="poker_submit" onclick='javascript:os_poker_trigger("os_poker_jump", {url:"<?php print url("poker/shop/get_chips", array("query" => array("height" => 442, "width" => 603), "absolute" => FALSE)); ?>", lightbox:true});'>
				<div class='pre'>&nbsp;</div>
				<div class='label' style="width: 60px; text-align: center;"><?php print(t("Get more")) ?></div>
				<div class='user_login_clear'></div>
			</div>
		</div>
		<div class="user_login_brief_row">
			<p class="chips_count">
				<?php print t("You have !chips Chips", array("!chips" => "<u><b class='chips'>" . $os_user->Chips(TRUE) . "</b></u>")); ?>
			</p>
		</div>
	</div>
</div>
