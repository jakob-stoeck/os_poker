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
<div id="play-now">
	<div id="play-now-inner">
		<p class="link">
			<a id="play-now-button" href="javascript:void(0);" onclick="javascript:os_poker_play_now_clicked();"><? t('Play now') ?></a>
		</p>
		<p class="text">
			<?php print $online . " " . t("Players Online Now") . " !"; ?>
		</p>
	</div>
</div>
