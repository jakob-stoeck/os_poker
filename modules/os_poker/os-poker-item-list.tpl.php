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
		$nitem = 0;
		$page = (isset($_GET["page"]) ? $_GET["page"] : 0);
		$perPage = 15;
		$toprint = array();

		if ($items && count($items) > 0)
		{
			$begin = $page * $perPage;
			$end = $begin + $perPage;

			foreach ($items as $item)
			{
				if ($item->available != FALSE)
				{
					if ($nitem >= $begin && $nitem < $end)
					{
						$toprint[] = theme("os_poker_item", $item, FALSE);
					}
					++$nitem;
				}
			}
		}

?>

<div class="ajax-pager">
	<?php 
		if ($nitem > $perPage)
		{
			$GLOBALS['pager_page_array'][0] = $page; //what page you are on
			$GLOBALS['pager_total'][0] = (int)($nitem / $perPage); // total number of pages
			if ($nitem % $perPage)
				$GLOBALS['pager_total'][0] = $GLOBALS['pager_total'][0] + 1;
			print theme('pager', array(), $perPage, 0, array("ajax" => TRUE, "list" => "items"), 5);
		}
	?>
</div>
<div class="inner-item-list">
	<?php
		print implode(" ", $toprint);
	?>
</div>
