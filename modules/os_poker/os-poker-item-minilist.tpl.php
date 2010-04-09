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
