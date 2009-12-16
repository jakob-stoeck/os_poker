<div id="messagebox">
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="block_title_bar block_title_text"><?php print t("Messages"); ?></div>
	<div id="message-list">
		<?php
			print theme('os_poker_message_list', $messages);
		?>
	</div>
</div>