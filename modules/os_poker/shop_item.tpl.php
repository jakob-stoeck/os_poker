<div class="shop-item">
	<a href="javascript:void(0);" <?php if ($selected == TRUE) print 'class="selected"'; ?> onclick="javascript:os_poker_setup_shop_item_select($(this), <?php print $item->price; ?>, <?php print $item->id_item; ?>);">
		<span>
			<img src="<?php print $item->picture; ?>" alt="<?php print t($item->name); ?>" />
		</span>
		<span class="Text">
			<?php print $item->name; ?>
		</span>
		<span class="Text">
			<?php print $item->FormatedPrice(); ?>
		</span>
	</a>
</div>