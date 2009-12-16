$(document).ready(function () {
	
	os_poker_input_file_style();
	os_poker_message_start();
	os_poker_init_events();
	os_poker_init_menu();
	
	os_poker_register_slider($('#buddy_panel'), 80);
	
	$("#buddylist #edit-online").click(function () {
	
		os_poker_filter_online($('#buddy_panel .buddy_list_block'), this.checked);
		os_poker_slider_reset($('#buddy_panel .cursor'));
	});
	
});