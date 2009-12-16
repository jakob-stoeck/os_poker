<div class="message">
	<?php if ($message) {?>
		<div class="type fleft">
			<img src="<?php print $message["symbol"]; ?>" alt="symbol"/>
		</div>
		<div class="body fleft">
			<?php 
				print t($message["text"], array("!user" => $message["sender"]));
				
				if (isset($message["links"]))
				{
					print " | " . t($message["links"]);
				}
			?>
		</div>
		<div class="sender fleft">
			<img src="<?php print $message["senderPix"]; ?>" alt="sender"/>
		</div>
		<div class="clear"></div>
	<?php } ?>
</div>