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
<div class="inner-item-list">
	<?php
		$nreward = 0;
		$page = (isset($_GET["page"]) ? $_GET["page"] : 0);
		$perPage = 4;
		
		if ($target_user)
		{
			$rewards = $target_user->Rewards();
			usort($rewards, "_os_poker_sort_rewards");
			$begin = $page * $perPage;
			$end = $begin + $perPage;
			foreach($rewards as $key => $value)
			{
				if ($value["value"] != 0)
				{
					if ($nreward >= $begin && $nreward < $end)
					{
						print '<img title="' . $value["name"] . '" src="' . $value["picture"] . '" alt="' . $value["name"] . '"/>';
					}
					$nreward++;
				}
			}
		}
	?>
</div>
<div class="ajax-pager">
	<?php
		if ($nreward > $perPage)
		{
			$GLOBALS['pager_page_array'][0] = $page; //what page you are on
			$GLOBALS['pager_total'][0] = (int)($nreward / $perPage); // total number of pages
			if ($nreward % $perPage)
				$GLOBALS['pager_total'][0] = $GLOBALS['pager_total'][0] + 1;
			print theme('pager', array(), $perPage, 0, array("ajax" => TRUE, "list" => "rewards"), 5);
		}
	?>
</div>
