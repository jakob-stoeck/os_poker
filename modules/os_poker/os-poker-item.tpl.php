<div class="shop-item">
	<a href="javascript:void(0);" title="<?php print htmlspecialchars(t($item->name)); ?>"  <?php if ($selected == TRUE) print 'class="selected"'; ?> onclick="javascript:os_poker_setup_shop_item_select($(this), <?php print $item->price; ?>, <?php print $item->id_item; ?>);">
		<span>
			<img src="<?php print $item->picture; ?>" alt="<?php print htmlspecialchars(t($item->name)); ?>" />
		</span>
		<span class="Text">
			<?php print htmlspecialchars($item->name); ?>
		</span>
		<span class="Text">
			<?php print $item->FormatedPrice(); ?>
		</span>
	</a>
</div>