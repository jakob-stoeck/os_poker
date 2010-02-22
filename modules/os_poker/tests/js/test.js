QUnit.log = function(result, message)  
{  
  if (window.console && window.console.log)  
  {  
    window.console.log(result +' :: '+ message);  
  }  
}  

Drupal.settings.basePath = '/test/';

module('os_poker.event', {

});

function mockFn(){
  var c = function() {
    c.count++;
    c.history.push(arguments);
  }
  c.count = 0;
  c.history = [];
  return c;
};

test('os_poker_init_events', 9, function(){

  var originalHandlers = OsPoker.eventHandlers;
  OsPoker.eventHandlers = {
    simple_callback: mockFn(),
    callback_with_selector: {
        fn: mockFn(),
        selector: '#main'
    },
    callback_without_selector: {
        fn: mockFn()
    }
  };
  os_poker_init_events();
  os_poker_trigger('simple_callback', 'foo');
  os_poker_trigger('callback_with_selector', 'bar');
  os_poker_trigger('callback_without_selector', 'foobar');
  equals(OsPoker.eventHandlers.simple_callback.count, 1, 'simple_callback has been used once');
  equals(OsPoker.eventHandlers.simple_callback.history[0][0].data, undefined, 'with an undefined data property in the first arguments,');
  equals(OsPoker.eventHandlers.simple_callback.history[0][1], 'foo', 'and with the expected second argument');
  equals(OsPoker.eventHandlers.callback_with_selector.fn.count, 1, 'callback_with_selector has been used once,');
  equals(OsPoker.eventHandlers.callback_with_selector.fn.history[0][0].data.selector, '#main', 'with the expected data property in the first arguments,');
  equals(OsPoker.eventHandlers.callback_with_selector.fn.history[0][1], 'bar', ',and with the expected second argument');
  equals(OsPoker.eventHandlers.callback_without_selector.fn.count, 1, 'callback_without_selector has been used once,');
  equals(OsPoker.eventHandlers.callback_without_selector.fn.history[0][0].data, undefined, 'with an undefined data property in the first arguments,');
  equals(OsPoker.eventHandlers.callback_without_selector.fn.history[0][1], 'foobar', 'and with the expected second argument');
  OsPoker.eventHandlers = originalHandlers;
});

module("os_poker_table_selected", {
	setup: function() {
    var ajax = function(options) {
      $.ajax.options = options;
    };
    ajax.jquery_ajax = $.ajax;
    $.ajax = ajax;
    var table_users = $('<div id="table_users">').appendTo('#main');
    $('<div class="header"></div>').appendTo(table_users);
    var list = $('<div class="list splash">').appendTo(table_users);
    $('<div class="userlist">').appendTo(list);
    window.tb_init = mockFn;
    os_poker_init_events();
    os_poker_trigger('os_poker_table_selected', {table: 0});
	},
	teardown: function() {
    $.ajax = $.ajax.jquery_ajax;
	}
});

test("with users", 4, function() {
  ok($.ajax.options && $.ajax.options.complete, '$.ajax has been called with a complete callback');
  var html = '<div class="userlist"><div class="user"></div><div class="user"></div></div>';
  $.ajax.options.complete({responseText: html}, 'success');
	equals($('.list').hasClass('splash'), false, '.list container should not have splash class');
	equals($('.list').html(), html, '.list contains the returned markup');
  ok(!!$('#table_users .header:visible').length, 'Header is visible.');
});

test("without users", 4, function() {
  ok($.ajax.options && $.ajax.options.complete, '$.ajax has been called with a complete callback');
  var html = '<div class="userlist"></div>';
  $.ajax.options.complete({responseText: html}, 'success');
	equals($('.list').hasClass('splash'), true, '.list container should not have splash class');
	equals($('.list').html(), '', '.list contains the returned markup');
  ok(!$('#table_users .header:visible').length, 'Header is not visible.');
});

test("error in request", 4, function() {
  ok($.ajax.options && $.ajax.options.complete, '$.ajax has been called with a complete callback');
  var html = 'FAIL!';
  $.ajax.options.complete({responseText: html}, 'failure');
	equals($('.list').hasClass('splash'), true, '.list container should not have splash class');
	equals($('.list').html(), '', '.list should be empty');
  ok(!$('#table_users .header:visible').length, 'Header is not visible.');
});

module("os_poker_messages", {
	setup: function() {
    var ajax = function(options) {
      $.ajax.options = options;
    };
    ajax.jquery_ajax = $.ajax;
    $.ajax = ajax;
		
		var  os_poker_site_root = function() {return 'http://drupal-dev.pokersource.info/drupal6/';};
		
		os_poker_bind_message('bar', undefined, function(event, arg) {
			equals(event.type, 'bar', 'bar event triggered');
			equals(arg, 'zzz');
	    });
		
		os_poker_message_start(false);
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

test("os_poker_messagebox_reset", function() {
	expect(2);
		
	os_poker_init_messagebox();
	
	$.ajax.options.success({messages: [{type: 'os_poker_messagebox', body: {inbox:42, picture:"sites/all/modules/os_poker/images/mailbox.png"}}]});
	
	Drupal.behaviors.mesageboxResetBehavior();

	var pix = $("#mbox_pix");										
	var count = $("#mbox_count");	
	
	equals(pix.is(':hidden'), true, "Picture hidden");
	equals(count.text(), "", "Message count reset");
});

module("os_poker.toolkit", {
	setup: function() {
	
	},
	teardown: function() {

	}
});


test("os_poker_site_root", function() {
	expect(2);
	
	equals(os_poker_site_root(), "/test/", "Root path");
	Drupal.jsEnabled = false;
	equals(os_poker_site_root(), "/", "Default root path");

});

/*
**
*/

module("os_poker.events", {
	setup: function() {
		$("#main").append('<div>Chips : <b class="chips">$ 42</b></div>');
	
		os_poker_init_events();
	},
	teardown: function() {

	}
});


test("event os_poker_update_chips", function() {
	os_poker_trigger("os_poker_update_chips", {amount: os_poker_number_format(1000)});
	equals($("b.chips").text(), "$ 1,000", "Event received, number formated and updated");
  os_poker_trigger("os_poker_update_chips", {amount: 1000});
	equals($("b.chips").text(), "$ 1,000", "Event received, number formated and updated");
});

module('Drupal.behaviors.os_poker', {
  setup: function(){
    //mockup function for Thickbox
    window.tb_show = window.tb_remove = window.tb_init = function(){};
  },
  teardown: function(){}
});

test('should not thrown an uncaught exception when an expected element is missing', 1, function(){
  try {
    Drupal.behaviors.os_poker($('<div></div>')[0]);
    ok(true, 'no uncaught exception thrown');
  } catch (exception) {
    ok(false, 'uncaught exception thrown');
  }
})

module('Drupal.behaviors.os_poker', {
  setup: function(){
    this.tb_remove = this.tb_show = 0;
    var testContext = this;
    //create mockup functions for thickbox
    window.tb_show = function(){
      testContext.tb_show += 1;
    };
    window.tb_remove = function() {
      testContext.tb_remove += 1;
    }
    window.tb_init = function(){};
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
  Drupal.behaviors.os_poker($('#main')[0]);
  Drupal.behaviors.os_poker($('#main')[0]); //This second call is intentional to check the behavior does not process the same element twice
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
asyncTest('status messages are not shown if Drupal.settings.os_poker.inline_messages is set to TRUE', 2, function(){
  Drupal.settings.os_poker = {inline_messages: true};
  Drupal.behaviors.os_poker($('#main')[0]);
  $(document.body).removeClass('page-admin');
  ok(!$('.messages-popup').length, 'no message popup container should be created');
  var testContext = this;
  setTimeout(function(){
    equals(testContext.tb_show, 0, 'tb_show should not be called');
    start();
  }, 0);
});

module('os_poker.slider', {
  setup: function(){
    var c = $('<div class="cursor"></div>').appendTo('#main');
    for (i = 0; i < 10; i++) {
      $('<div />').attr('id', 'child' + i).css('float', 'left').appendTo(c);
    }
  },
  teardown: function(){}
});

test('', 1, function(){
  var expectedCursorInnerWidth = 0;
  $('#main .cursor > :visible').each(function(){
      var innerWidth = Math.ceil(Math.random() * 80);
      var rightBorderWidth = Math.ceil(Math.random() * 4);
      var leftBorderWidth = Math.ceil(Math.random() * 4);
      expectedCursorInnerWidth +=  innerWidth + rightBorderWidth + leftBorderWidth;
      $(this).css({
        'height': '10px',
        'width': innerWidth + 'px',
        'border-right': 'solid ' + rightBorderWidth + 'px black',
        'border-left': 'solid ' + leftBorderWidth + 'px black'
      });
  });
  os_poker_slider_reset($('#main .cursor'));
  ok(expectedCursorInnerWidth <= $('#main .cursor').innerWidth(), 'After calling os_poker_slider_reset, cursor with should be larger enough to display all its children on a single line.');
});

module('os_poker.tourney-notify', {
  setup: function(){
    var template = $('<div id="tourney-notify-template">Some message <span></span></div>').appendTo('#main');
  },
	teardown: function(){$("#tourney-notify").remove();}
});

test('', function() {
	expect(7);
	
    	os_poker_init_tourney_notify();
	equals($("#tourney-notify-template").length, 0, "Notify template is removed");
	ok($("#tourney-notify").length, 'tourney notify popup is initialized');
	ok($("#tourney-notify").is(":hidden"), 'tourney notify popup should be hidden by default');
	window.top.os_poker_tourney_start_notify("My tourney", 100, true);
	ok(!$("#tourney-notify").is(":hidden"), 'tourney notify popup should show when triggered');
	$("#tourney-notify .close-button").trigger('click');
	equals($("#tourney-notify .notify-text a").html(), "My tourney/100", "Tourney name and table should be displayed");
	equals($("#tourney-notify .notify-text a").attr('href'), os_poker_site_root() + '?view=table&game_id=100', "Tourney name and table should be linked to the table");

	ok($("#tourney-notify").is(":hidden"), 'tourney notify popup should be hidden when close button is clicked.');
	
});

