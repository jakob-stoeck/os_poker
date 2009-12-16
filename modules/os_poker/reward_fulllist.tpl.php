<div class="inner-item-list">
	<?php
		$nreward = 0;
		$page = (isset($_GET["page"]) ? $_GET["page"] : 0);
		$perPage = 15;
		
		if ($rewards)
		{
			$begin = $page * $perPage;
			$end = $begin + $perPage;

			foreach($rewards as $key => $value)
			{
				if ($nreward >= $begin && $nreward < $end)
				{
					?>
						<div class="reward">
						<div>
							<img alt="Badge" src="<?php print $value["picture"] ?>"  />
						</div>
						<div class="text">
							<?php print $value["name"] ?>
						</div>
							<div title="<?php print $value["desc"] ?>" class="mask <?php if ($value["value"] == 0) { print "disabled"; } ?>">&nbsp;</div>
						</div>
					<?php
				}
				$nreward++;
			}
		}
	?>
	<div class="clear"></div>
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
