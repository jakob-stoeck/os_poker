<div class="message">
	<?php if ($message) {?>
		<div class="type fleft">
			<img src="<?php print $message["symbol"]; ?>" alt="symbol"/>
		</div>
		<div class="body fleft">
			<?php 
				print t($message["text"], array("!user" => $message["sender"]));
				
				if ($message["links"])
				{
					if (isset($message["tags"]["type"]) && $message["tags"]["type"] == "buddy" && 
						isset($message["tags"]["sender"]))
					{
						$current_user = CUserManager::instance()->CurrentUser();
						$r_user = CUserManager::instance()->User($message["tags"]["sender"]);
						
						if ($r_user->BuddyRequested($current_user->uid))
						{
							print " | " . t($message["links"]);
						}
						else
						{
							$buddies = $current_user->Buddies();
							if (in_array($r_user->uid, $buddies)) 
							{
								print "<span class='a_link'> (" . t("Accepted") . ")</span>";
							}
							else
							{
								print "<span class='r_link'> (" . t("Refused") . ")</span>";
							}
						}
					}
					else
					{
						print " | " . t($message["links"]);
					}
				}
			?>
		</div>
		<div class="sender fleft">
			<img src="<?php print $message["senderPix"]; ?>" alt="sender"/>
		</div>
		<div class="clear"></div>
	<?php } ?>
</div>