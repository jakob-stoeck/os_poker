<?php
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
