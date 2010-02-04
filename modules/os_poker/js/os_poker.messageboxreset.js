/*
 * this script is included in the messagebox iframe, and resets the unread message count in the navbar.
 */ 
function os_poker_reset_messagebox() {
	var mbox_container = $("#block-menu-menu-messages-links a", window.top.document);
	
	var pix = mbox_container.find("#mbox_pix");										
	var count = mbox_container.find("#mbox_count");						
	count.text('');
	pix.hide();
}


$(window).ready(function() {
	os_poker_reset_messagebox();
});

