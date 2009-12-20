module("os_poker_update_lobby", {
	setup: function() {
	    var table_users = $('<div id="table_users">').appendTo('#main');
	    var list = $('<div class="list splash">').appendTo(table_users);
	    $('<div class="userlist">').appendTo(list);
	},
	teardown: function() {
	}
});

test("with users", function() {
	expect(12);
	os_poker_update_lobby([{serial: 1, name: 'foo', chips: 100}, {serial: 2, name: 'bar', chips: 2000000}]);
	equals($('.list').hasClass('splash'), false, '.list container should not have splash class');
	equals($('.userlist .user').length, 2, '.userlist container should contain 2 users');

	equals($('.userlist .picture a').eq(0).attr('href'), '?q=user/1', "first user picture should link to user/1");
	equals($('.userlist .picture img').eq(0).attr('src'), 'sites/default/files/pictures/picture-1.png', "first user picture src should be picture-1.png");
	equals($('.userlist .name a').eq(0).text(), 'foo', 'first user name should be foo');
	equals($('.userlist .name a').eq(0).attr('href'), '?q=user/1', 'first user name should link to user/1');
	equals($('.userlist .money a').eq(0).text(), '$ 1', "foo's money should be $ 1");

	equals($('.userlist .picture a').eq(1).attr('href'), '?q=user/2', "first user picture should link to user/2");
	equals($('.userlist .picture img').eq(1).attr('src'), 'sites/default/files/pictures/picture-2.png', "first user picture src should be picture-1.png");
	equals($('.userlist .name a').eq(1).text(), 'bar', 'first user name should be bar');
	equals($('.userlist .name a').eq(1).attr('href'), '?q=user/2', 'first user name should link to user/1');
	equals($('.userlist .money a').eq(1).text(), '$ 20,000', "foo's money should be $ 20,000");
});

test("without users", function() {
	expect(2);
	os_poker_update_lobby([]);
	equals($('.list').hasClass('splash'), true, '.list container should have splash class');
	equals($('.userlist .user').length, 0, '.userlist container should contain no users');
});

module("os_poker_messages", {
	setup: function() {
	    var ajax = function(options) {
		$.ajax.options = options;
	    };
	    ajax.jquery_ajax = $.ajax;
	    $.ajax = ajax;
		
		var  os_poker_site_root = function() { return 'http://drupal-dev.pokersource.info/drupal6/'; };
		
		os_poker_bind_message('bar', undefined, function(event, arg) {
			equals(event.type, 'bar', 'bar event triggered');
			equals(arg, 'zzz');
	    });
	},
	teardown: function() {
	    $.ajax = $.ajax.jquery_ajax;
		
		$(document).unbind('bar');
	}
});

test("os_poker_send_message", function() {
	expect(2);
	os_poker_send_message('foo');
	$.ajax.options.success({messages: [{type: 'bar', body: 'zzz'}]});
});

test("os_poker_message_listen", function() {
	expect(2);
	os_poker_message_listen();
	$.ajax.options.success({messages: [{type: 'bar', body: 'zzz'}]});
	os_poker_message_shutdown();
});


module("os_poker.messagebox", {
	setup: function() {
	
		var ajax = function(options) {
		$.ajax.options = options;
	    };
	    ajax.jquery_ajax = $.ajax;
	    $.ajax = ajax;

		$("#main").append('<div id="block-menu-menu-messages-links"><a href="javascript:void(0);">Messages</a></div>');
	},
	teardown: function() {
	     $.ajax = $.ajax.jquery_ajax;
	}
});


test("os_poker_init_messagebox", function() {
	expect(4);
		
	os_poker_init_messagebox();
	
	$.ajax.options.success({messages: [{type: 'os_poker_messagebox', body: {inbox:42, picture:"sites/all/modules/os_poker/images/mailbox.png"}}]});
	
	var pix = $("#mbox_pix");										
	var count = $("#mbox_count");	
	
	equals(pix.length, 1, "Message picture container append");
	equals(count.length, 1, "Message count container append");
	
	equals(pix.attr('src'), "sites/all/modules/os_poker/images/mailbox.png", "Picture displayed");
	equals(count.text(), " 42 ", "Message count set");
});


