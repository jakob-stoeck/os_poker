<div id="buddylist">
	<div class="header block_title_bar block_title_text">
		<div class="fleft">
			<?php print $title; ?>
		</div>
		<div class="fleft">
			<?php print $filter_form; ?>
		</div>
		<div class="clear"></div>
	</div>



	<div id="buddy_panel">

		<a href="javascript:void(0);" class="previous">&nbsp;</a>
		<div class="list">
			<?php
				$buddies = $current_user->Buddies(TRUE);
				$rcount = count($buddies);
				$limit = $rcount;
				$placeholder = FALSE;
				if ($rcount < 7)
				{
					$limit = 7;
					$placeholder = TRUE;
				}
			?>
			<div class="cursor">
				<?php 
					for ($i = 0; $i < $limit; ++$i)
					{
						if ($placeholder && $i >= $rcount)
						{
							print theme('buddy_block', NULL, $i + 1);
						}
						else
						{
							$buddy = &$buddies[$i];
							print theme('buddy_block', $buddy, $i + 1);
						}
					}
				?>
			</div>
		</div>
		<a href="javascript:void(0);" class="next">&nbsp;</a>


		<div id="today_gift">
		     <?php if ($current_user->CanDailyGift()) { ?>
								<img width="115px" height="165px" style="cursor: pointer;" onclick="javascript:os_poker_send_message({type: 'os_poker_daily_gift'}); this.style.cursor=''; this.src='<?php  print drupal_get_path('theme', 'poker'); ?>/images/banner_invite_more_friends.jpg';" src="<?php  print drupal_get_path('theme', 'poker'); ?>/images/daily_gift.png" alt="Today's free gift"/>
		     <?php } else { ?>
			       <img width="112px" height="166px" src="<?php  print drupal_get_path('theme', 'poker'); ?>/images/banner_invite_more_friends.jpg" alt="Today's free gift"/>
		     <?php } ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
