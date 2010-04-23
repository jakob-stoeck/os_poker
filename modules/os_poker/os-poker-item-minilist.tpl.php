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
	$nitem = 0;
	$page = (isset($_GET["page"]) ? $_GET["page"] : 0);
	$perPage = 4;
	$nPages = 0;
	
	if ($target_user)
	{
		$inventory = $target_user->Items();
		$nitem = count($inventory);
		$nPages = (int)($nitem / $perPage);
		if ($nitem % $perPage) ++$nPages;
		$page = min($page, $nPages);
		$begin = $page * $perPage;
		$end = $begin + $perPage;
		$end = min($end, $nitem);
		
		for ($i = $begin; $i < $end; ++$i)
		{
			$item = $inventory[$i];
			print '<img ' . (($item->active == 1) ? ("class='active'") : ("")) . ' title="' . t($item->item->name) . '" src="' . $item->item->picture . '" alt="' . t($item->item->name) . '"/>';
		}
	}
?>
</div>
<div class="ajax-pager">
	<?php
		if ($nitem > $perPage)
		{
			$GLOBALS['pager_page_array'][0] = $page; //what page you are on
			$GLOBALS['pager_total'][0] = $nPages; // total number of pages
			print theme('pager', array(), $perPage, 0, array("ajax" => TRUE, "list" => "item"), 5);
		}
	?>
</div>
