<div id="buddies-list">
<?php	if ($buddies && count($buddies) > 0) : ?>

	<?php
$page = $_GET["page"];
$items_per_page = 8;
$page_total = (int)(count($buddies) / $items_per_page);

if (count($buddies) % $items_per_page)
	++$page_total;

for ($i = 0; $i < $items_per_page; $i++)
  {
  $buddies_entry = $buddies[$items_per_page * $page + $i];
  if ($buddies_entry)
    /*	foreach ($buddies as $buddies_entry)*/
    {
		$oBuddy->name = $buddies_entry->profile_nickname;
		$oBuddy->uid =  $buddies_entry->uid;

	?>
		<div class='buddy_result_list_entry'>

			<div class="buddy_result_list_picture">
			<?php
				if (variable_get('user_relationships_show_user_pictures', 0))
				{
					print theme('user_picture', $buddies_entry);
				}
			?>
			</div>

			<div class="buddy_result_list_infos">
				<div class="buddy_result_list_name <?php if ($buddies_entry->Online()) { print "online"; } ?>">
					<?php
						print theme('username', $buddies_entry);
					?>
				</div>
				<div class="buddy_result_list_chips">
					<?php print $buddies_entry->Chips(TRUE); ?>
				</div>
				<div class="buddy_result_list_level">
					<?php print $buddies_entry->Status(); ?>
				</div>
				<div class="buddy_result_list_city">
					<?php print $buddies_entry->profile_city; ?>
				</div>
			</div>
			<div class="buddy_result_list_links">
				<div class="link_challenge poker_submit silver" onclick="javascript:os_poker_send_message({type :'os_poker_challenge_user', challengetarget: <?php print $buddies_entry->uid; ?>});">
					<div class="pre"> </div>
					<div class="label" style="width: 50px; text-align: center;">
						<?php print t("Challenge"); ?>
					</div>
				</div>

				<?php

					if ($buddies_entry->Tables())
					{
						$text = t("Join table");
            $url = 'window.top.document.location.href =\'' . url('user/'. $buddies_entry->uid .'/table').'\'';
					}
					else
					{
						$text = t("Invite now");
						$url = "os_poker_send_message({type :'os_poker_invite_user', target: " . $buddies_entry->uid . "})";
					}

				?>
				<div class="link_invite poker_submit silver" onclick="javascript:<?php print $url; ?>;">
					<div class="pre"> </div>
					<div class="label" style="width: 50px; text-align: center;">
						<?php
							print $text;
						?>
					</div>
				</div>
				<?php if ($buddies_entry->profile_accept_gifts == 0) { ?>
					<div class="link_send_gifts poker_submit silver" onclick="javascript: document.location.href ='<?php print url("<front>", array("query" => "q=poker/shop/shop/1/buddy/" . $buddies_entry->uid)); ?>';"  >
						<div class="pre"> </div>
						<div class="label" style="width: 50px; text-align: center;">
							<?php print t("Send gifts"); ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?
	}
  }
?>
<div class="BannerTop">
 <a title="" href="?q=poker/shop/get_chips">
    <img src="<?php  print drupal_get_path('theme', 'pbpoker'); ?>/images/buychips.png" alt="" />
 </a>
</div>
<div class="BannerBottom">
 <a title="" href="?q=poker/buddies/invite">
    <img src="<?php  print drupal_get_path('theme', 'pbpoker'); ?>/images/invitefriends.png" alt="" />
 </a>
</div>

<div class="clear"></div>

<div class="footer">
<?php
$GLOBALS['pager_page_array'][] = $page; //what page you are on
$GLOBALS['pager_total'][] = $page_total; // total number of pages
print theme('pager', NULL, $items_per_page);
?>
</div>

<?php else : ?>
	<?php
		if ($action == "search")
		{
		  $error_message = "<h1>" . t("Sorry !") . "</h1>" . t("your Searchrequest didn't match any Players.") . "<br/><br/>". "<div class='poker_submit'><div class='pre'>&nbsp;</div><div class='label'>" . l(t("Try again"), 'poker/buddies/search') . "</div></div>";


		}
		else
		{
			$error_message = t("You don't have any buddy.") . "<br/>". l(t("Invite a buddy"), 'poker/buddies/invite');
		}

		print theme('poker_error_message', $error_message);
	?>

<div class="BannerTop">
 <a title="" href="?q=poker/buddies/invite">
	<img src="<?php  print drupal_get_path('theme', 'pbpoker'); ?>/images/invitefriends.png" alt=""/>
 </a>
</div>
<div class="BannerBottom">
 <a title="" href="?q=poker/shop/get_chips">
	<img src="<?php  print drupal_get_path('theme', 'pbpoker'); ?>/images/buychips.png" alt=""/>
 </a>
</div>

<div class="clear"></div>

<?php endif; ?>
</div>