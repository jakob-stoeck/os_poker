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
<div class="shop-item">
	<a href="javascript:void(0);" title="<?php print htmlspecialchars(t($item->name)); ?>"  <?php if ($selected == TRUE) print 'class="selected"'; ?> onclick="javascript:os_poker_setup_shop_item_select($(this), <?php print $item->price; ?>, <?php print $item->id_item; ?>);">
		<span>
			<img src="<?php print $item->picture; ?>" alt="<?php print htmlspecialchars(t($item->name)); ?>" />
		</span>
		<span class="Text">
			<?php print htmlspecialchars($item->name); ?>
		</span>
		<span class="Text">
			<?php print $item->FormatedPrice(); ?>
		</span>
	</a>
</div>
