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
<div class="inner-item-list dotted-red-border">
	<?php
		$nusers = 0;
		$page = (isset($_GET["page"]) ? $_GET["page"] : 0);
		$perPage = 10;
		if ($sorted_users)
		{
			$begin = $page * $perPage;
			$end = $begin + $perPage;

			foreach($sorted_users as $key => $value)
			{
				if ($nusers >= $begin && $nusers < $end)
				{
					print theme('buddy_block', $value, ($key + 1), TRUE);
				}
				++$nusers;
			}
		}
	?>
	<div class="clear"></div>
</div>
<div class="ajax-pager">
	<?php 
		if ($nusers > $perPage)
		{
			$GLOBALS['pager_page_array'][0] = $page; //what page you are on
			$GLOBALS['pager_total'][0] = (int)($nusers / $perPage); // total number of pages
			if ($nusers % $perPage)
				$GLOBALS['pager_total'][0] = $GLOBALS['pager_total'][0] + 1;
			print theme('pager', array(t("« first"), t("‹ previous"), null, t("next ›"), t("last »")), $perPage, 0, array("ajax" => TRUE, "list" => "ranking"), 5);
		}
	?>
</div>
