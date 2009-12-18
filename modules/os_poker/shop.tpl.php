<?php 

	if ($params)
	{
		extract($params);
	}

?>
<div id="shop">
	<div class="header">
		<div class="left">
			<?php
				if ($subtarget)
				{
					print "<div class=\"shop_user" . (($subtarget->Online()) ? (" online") : ("")) . "\">" . $subtarget->profile_nickname . "</div>";
				}
			?>
		</div>
		<div class="right">
			
		</div>
	</div>
	<div class="panel">
		<div class="left">
			<div class="top">
			<?php
				if ($subtarget)
				{
					print '<img src="' . $subtarget->picture . '" alt="user"/>';
				}
			?>
			</div>
			<div class="bottom">
			<ul class="categories">
			<?php
				if ($categories)
				{
					foreach ($categories as $id_category => $category_name)
					{
						$slink = "{$id_category}/";
						
						if ($target_type) { $slink .= "{$target_type}/"; }
						if ($target_id) { $slink .= "{$target_id}/"; }
						if ($subtarget_id) { $slink .= "{$subtarget_id}"; }
					
						print 	"<li " . (($id_category == $current_category) ? 'class="active" >' : ">") .
								l(t($category_name), "poker/shop/shop/{$slink}") . "</li>";
					}
				}
			?>
			</ul>
			</div>
		</div>
		<div class="right">
			<div id="items">
				<?php
					$fitem = new CItem();
				
					if (!$error)
					{
						if ($items)
						{
							$first = FALSE;
							
							foreach ($items as $item)
							{
								if ($item->available != FALSE)
								{
									print theme("os_poker_item", $item, $first);
									
									if ($first)
										$fitem = $item;
								}
								$first = FALSE;
							}
						}
					}

				?>
			</div>
		</div>
	</div>
	<div class="footer">
		<div class="left">
			<?php
				print t("Info:") . "<br/>" .  t("Purchase taken from Off-Table Chips.");
			?>
		</div>
		<div class="right">
			<?php if ($target_type == "table") : ?>
			<div class="bcontainer">
				<div class="poker_submit big disabled" onclick="javascript:os_poker_setup_shop_buy('special', $(this));">
					<div class="pre"> </div>
					<div class="label"><?php print t("Buy for Table and Buddies") . "<br/>(<span mul='" . count($special) . "' class='total'>" . _os_poker_format_chips($fitem->price * count($special)) . "</span>)"; ?></div>
					<div class="user_login_clear"></div>
				</div>
			</div>
			<div class="bcontainer">
				<div class="poker_submit big disabled" onclick="javascript:os_poker_setup_shop_buy('target', $(this));">
					<div class="pre"> </div>
					<div class="label"><?php print t("Buy for Table") . "<br/>(<span mul='" . count($target) . "' class='total'>" . _os_poker_format_chips($fitem->price * count($target)) . "</span>)"; ?></div>
					<div class="user_login_clear"></div>
				</div>
			</div>
			<?php else : ?>
			<div class="bcontainer">
				<div class="poker_submit big disabled" onclick="javascript:os_poker_setup_shop_buy('target', $(this));">
					<div class="pre"> </div>
					<div class="label"><?php print t("Buy for Buddies") . "<br/>(<span mul='" . count($target) . "' class='total'>" . _os_poker_format_chips($fitem->price * count($target)) . "</span>)"; ?></div>
					<div class="user_login_clear"></div>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($subtarget) : ?>
			<div class="bcontainer">
				<div class="poker_submit big disabled" onclick="javascript:os_poker_setup_shop_buy('subtarget', $(this));">
					<div class="pre"> </div>
					<div class="label"><?php print t("Buy") . "<br/>(<span mul='1' class='total'>" . $fitem->FormatedPrice() . "</span>)"; ?></div>
					<div class="user_login_clear"></div>
				</div>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
			<form id="buy_form" action="<?php print url("poker/shop/shop/{$current_category}/{$target_type}/{$target_id}/{$subtarget_id}"); ?>" method="post">
				<input id="buy_form_action" type="hidden" name="shop_action" value="" />
				<input id="buy_form_item" type="hidden" name="shop_item" value="<?php print $fitem->id_item; ?>" />
				<input type="submit" style="display: none;" value="Send" name="op"/>
			</form>
		</div>
	</div>
</div>