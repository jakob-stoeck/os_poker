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
				$limit = 0;
			
				if ($current_user)
				{
					$buddies = $current_user->Buddies(TRUE);
					$rcount = count($buddies);
					$limit = $rcount;
					$placeholder = FALSE;
					if ($rcount < 7)
					{
						$limit = 7;
						$placeholder = TRUE;
					}
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

    <?php print $daily_gift ?>
		<div id="invite_friends">
		</div>
		<div class="clear"></div>
	</div>
</div>
