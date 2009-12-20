$(document).ready(function () {
	os_poker_input_file_style();
	os_poker_message_start(false);
	os_poker_init_events();
	os_poker_init_pager();
	
	os_poker_register_slider($("#item_panel"), 50);
	
	$("#message-list .inner-item-list a").each(function() {
	
		if ($(this).hasClass("user_relationships_popup_link") == false && $(this).hasClass("noreplace") == false)
		{
			var tg = $(this).attr("href");
			
			$(this).attr("href", "javascript:void(0);");
			$(this).click(function() { parent.os_poker_trigger("os_poker_jump", {url:tg + "&height=442&width=603", lightbox:true}); });
		}
	});
	
});