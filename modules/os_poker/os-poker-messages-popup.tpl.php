<div id="messagebox">
<?php

   /*
$errors = drupal_get_messages('error');
foreach($errors['error'] as $error) {
    if($error != 'my error message') {
   drupal_set_message($error, 'error');
   }
  print $error;
}
   */

?>

<div id="messages_in_popup">
AJAXING...
</div>

	<div id="user_relationships_popup_form" class="user_relationships_ui_popup_form" style="width:282px;"></div>
	<a class="LayerClose" onclick="javascript:parent.tb_remove();" href="javascript:void(0);">&nbsp;</a>
	<div class="block_title_bar block_title_text"><?php print t("Messages"); ?></div>
	<div id="message-list">
	</div>
</div>

<a href="#" onclick="javascript:alert($(parent).find('#messages').html())">pwet </a>

<script type="text/javascript">
   $(document).ready(function(){
       alert($(parent).find("#messages").html());
   $("#messages_in_popup").html($("#messages").html());
     });
</script>
