<div id="shop-tabs" class="tabs-window">
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="tabs">
		<ul class="tabs primary">
		<li <?php if ($active_tab == "shop" || $active_tab == NULL) { print 'class="active"'; } ?>><?php print l(t("Chop Shop"), "poker/shop"); ?></li>
		<li <?php if ($active_tab == "get_chips") { print 'class="active"'; } ?>><?php print l(t("Get Chips"), "poker/shop/get_chips"); ?></li>
		</ul>
	</div>
	<div class="content">
		<?php print $content; ?>
	</div>
</div>
