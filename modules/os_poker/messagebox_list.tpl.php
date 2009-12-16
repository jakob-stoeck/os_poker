<div class="inner-item-list">
	<?php
		$nmsg = 0;
		$page = (isset($_GET["page"]) ? $_GET["page"] : 0);
		$perPage = 4;
		
		if ($messages && count($messages) > 0)
		{
			$begin = $page * $perPage;
			$end = $begin + $perPage;

			foreach($messages as $m)
			{
				if ($nmsg >= $begin && $nmsg < $end)
				{
					$mbody = json_decode($m->arguments, TRUE);
					print theme('os_poker_message', $mbody["body"]);
				}
				++$nmsg;
			}
		}
		else
		{
			print t("You don't have any message.");
		}	
	?>
</div>
<div class="ajax-pager">
	<?php 
		if ($nmsg > $perPage)
		{
			$GLOBALS['pager_page_array'][0] = $page; //what page you are on
			$GLOBALS['pager_total'][0] = (int)($nmsg / $perPage); // total number of pages
			if ($nmsg % $perPage)
				$GLOBALS['pager_total'][0] = $GLOBALS['pager_total'][0] + 1;
			print theme('pager', array(), $perPage, 0, array("ajax" => TRUE, "list" => "messages"), 5);
		}
	?>
</div>
