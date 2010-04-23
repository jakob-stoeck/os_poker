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
<div id="profile-tabs" class="tabs-window">
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="tabs">
		<ul class="tabs primary">
		<li <?php if ($active_tab == "profile" || $active_tab == NULL || $active_tab == "update") { print 'class="active"'; } ?>><?php print l(t("Profile"), "poker/profile" . (($external && isset($target_user))?"/profile/{$target_user->uid}":"")); ?></li>
		<li <?php if ($active_tab == "rewards") { print 'class="active"'; } ?>><?php print l(t("Rewards"), "poker/profile/rewards" . (($external && isset($target_user))?"/{$target_user->uid}":"")); ?></li>
		<li <?php if ($active_tab == "ranking") { print 'class="active"'; } ?>><?php print l(t("Ranking"), "poker/profile/ranking" . (($external && isset($target_user))?"/{$target_user->uid}":"")); ?></li>
		<?php if ($external == FALSE) { ?>
			<li <?php if ($active_tab == "settings") { print 'class="active"'; } ?>><?php print l(t("Settings"), "poker/profile/settings"); ?></li>
		<?php } ?>
		</ul>
	</div>
	<div class="content">
		<?php print $content; ?>
	</div>
</div>
