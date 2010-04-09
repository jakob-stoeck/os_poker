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
