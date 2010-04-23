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
<div id="buddylist">
	<div class="header block_title_bar block_title_text">
		<div class="fleft">
			<?php print $title; ?>
		</div>
		<div class="fleft">
			<?php print $filter_form; ?>
		</div>
		<div class="clear"></div>
	</div>



	<div id="buddy_panel">

		<a href="javascript:void(0);" class="previous">&nbsp;</a>
		<div class="list">
			<?php
				$limit = 0;
			
				if ($current_user)
				{
					$buddies = $current_user->Buddies(TRUE);
					$rcount = count($buddies);
					$limit = $rcount;
					$placeholder = FALSE;
					if ($rcount < 7)
					{
						$limit = 7;
						$placeholder = TRUE;
					}
				}
				
			?>
			<div class="cursor">
				<?php 
					for ($i = 0; $i < $limit; ++$i)
					{
						if ($placeholder && $i >= $rcount)
						{
							print theme('buddy_block', NULL, $i + 1);
						}
						else
						{
							$buddy = &$buddies[$i];
							print theme('buddy_block', $buddy, $i + 1);
						}
					}
				?>
			</div>
		</div>
		<a href="javascript:void(0);" class="next">&nbsp;</a>

    <?php print $daily_gift ?>
		<div id="invite_friends">
		</div>
		<div class="clear"></div>
	</div>
</div>
