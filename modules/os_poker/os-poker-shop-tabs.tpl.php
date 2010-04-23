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
<div id="shop-tabs" class="tabs-window">
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="tabs">
		<ul class="tabs primary">
		<li <?php if ($active_tab == "shop" || $active_tab == NULL) { print 'class="active"'; } ?>><?php print l(t("Shop"), "poker/shop"); ?></li>
		<li <?php if ($active_tab == "get_chips") { print 'class="active"'; } ?>><?php print l(t("Get Chips"), "poker/shop/get_chips"); ?></li>
		</ul>
	</div>
	<div class="content">
		<?php print $content; ?>
	</div>
</div>
