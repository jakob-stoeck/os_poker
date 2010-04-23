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
<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
<div class="tabs">
	<ul class="tabs primary">
		<li <?php if ($action == NULL) { print 'class="active"'; } ?>><a href="?q=poker/buddies" ><?php print t("Buddies"); ?></a></li>
		<li <?php if ($action == "search") { print 'class="active"'; } ?>><a href="?q=poker/buddies/search"><?php print t("Search"); ?></a></li>
		<li <?php if ($action == "invite" || $action == "invitedlist") { print 'class="active"'; } ?>><a href="?q=poker/buddies/invite"><?php print t("Invite Friends"); ?></a></li>
	</ul>
</div>
