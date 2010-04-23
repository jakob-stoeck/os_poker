<?php
//
//    Copyright (C) 2009, 2010 Pokermania
//    Copyright (C) 2010 OutFlop
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

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
          print theme('user_picture', $subtarget);
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
							print theme('os_poker_item_list', $items);
						}
					} else {
            print $error;
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
        <?php if ($subtarget->uid == $current_user->uid): ?>
        <div class="bcontainer">
          <div class="poker_submit big disabled activate-item" onclick="javascript:os_poker_setup_shop_buy('subtarget', $(this));">
            <div class="pre"> </div>
            <div class="label"><?php print t("Buy and activate") . "<br/>(<span mul='1' class='total'>" . $fitem->FormatedPrice() . "</span>)"; ?></div>
            <div class="user_login_clear"></div>
          </div>
        </div>
        <?php endif;?>
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
        <input id="buy_form_activate" type="hidden" name="shop_item_activate" value="0" />
				<input type="submit" style="display: none;" value="Send" name="op"/>
			</form>
		</div>
	</div>
</div>
