<div id="messagebox">
	<div id="user_relationships_popup_form" class="user_relationships_ui_popup_form" style="width:282px;"></div>
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="block_title_bar block_title_text"><?php print t("Messages"); ?></div>
	<div id="message-list">
		<?php
			print theme('os_poker_message_list', $messages);
		?>
	</div>
</div>