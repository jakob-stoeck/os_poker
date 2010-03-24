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
