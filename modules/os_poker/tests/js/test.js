QUnit.log = function(result, message)  
{  
  if (window.console && window.console.log)  
  {  
    window.console.log(result +' :: '+ message);  
  }  
}  

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

module('Message in Thickbox', {
  setup: function(){
    this.tb_remove = this.tb_show = 0;
    var testContext = this;
    //create mockup functions 
    window.tb_show = function(){
      testContext.tb_show += 1;
    };
    window.tb_remove = function() {
      testContext.tb_remove += 1;
    }
    window.os_poker_input_file_style = window.tb_init = function(){};
    //create Drupal-like HTML message markup
    $('#main').append('<div class="messages status"><ul><li>First status message</li><li>Second status message</il></ul></div>');
    $('#main').append('<div class="messages error"><ul><li>First error message</li><li>Second error message</il></ul></div>');
    $('#main').append('<div class="messages warning"><ul><li>First warning message</li><li>Second warning message</il></ul></div>');
  },
  teardown: function(){
    //delete mockup functions
    delete tb_show;
    delete os_poker_input_file_style;
    delete tb_init;
    delete tb_remove;
  }
});
asyncTest('status messages are shown in a thickbox', 6, function(){
  Drupal.behaviors.os_poker(document);
  Drupal.behaviors.os_poker(document); //This second call is intentional to check the behavior does not process the same element twice
  equals($('.messages-popup').length, 1, 'a unique message popup container should be created');
  ok($('.messages-popup .messages.status').length, 'the popup container should contain the status messages');
  ok($('.messages-popup .messages.warning').length, 'the popup container should contain the error messages');
  ok($('.messages-popup .messages.error').length, 'the popup container should contain the warning messages');
  var testContext = this;
  setTimeout(function(){
    equals(testContext.tb_show, 1, 'tb_show has been called once.');
    $('.messages-popup .close').click();
    ok(this.tb_remove, 'a click on the .close link in popup container should call tb_remove');
    start();
  }, 0);
  
});
asyncTest('status messages are not shown in a thickbox on administration page', 2, function(){
  $(document.body).addClass('page-admin');
  Drupal.behaviors.os_poker(document);
  $(document.body).removeClass('page-admin');
  ok(!$('.messages-popup').length, 'no message popup container should be created');
  var testContext = this;
  setTimeout(function(){
    equals(testContext.tb_show, 0, 'tb_show should not be called');
    start();
  }, 0);
});
