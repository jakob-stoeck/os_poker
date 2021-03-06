/*
 *    Copyright (C) 2009, 2010 Pokermania
 *    Copyright (C) 2010 OutFlop
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
var OsPoker = OsPoker || {};

/**
 * Drop call of tb_init('a.thickbox, area.thickbox, input.thickbox') on page load
 * in any nested (i)frame. Yes this is an ugly hack. But its needed since
 * thickbox is so unflexible.
 */
if(window != os_poker_get_top_window(window)) {
  //Everything is wrapped in an anynmous function to avoid pollution of the
  //global namespace.
  (function($){
    //put the original tb_init function in a variable
    var original = window.tb_init;
    //replace it with a wrapper function
    window.tb_init = function(domChunck) {
      if(domChunck != 'a.thickbox, area.thickbox, input.thickbox') {
        //this is not the document.ready call: execute the original tb_init
        original.apply(this, arguments);
      }
    };
  })(jQuery);
}

/**
 * Add event on $(document) before/after tb_show.
 */
if(typeof window.tb_show == 'function') {
  var original = window.tb_show;
  window.tb_show = function() {
    $(document).trigger('thickbox_show', arguments);
    original.apply(this, arguments);
    $(document).trigger('thickbox_show_after', arguments);
  }
}

//This should fix #187
$(document).bind('thickbox_show', function(event, caption, url, imageGroup) {
  if(url.indexOf('TB_iframe') != -1) {
    $('#TB_window').unload();
    $("#TB_ajaxContent").remove();
  }
  else {
    //$("#TB_iframeContent").remove();
  }
});

$(document).bind('thickbox_show_after', function(even, caption, url, imageGroup) {
  //Fix transparent background for Thickbox's iframe in IE
  $('#TB_iframeContent').attr('allowTransparency', 'allowTransparency');
  //Fix opening an jQuery UI Tabs's tab using fragment in a thickbox.
  var hash = url.split('#', 2)[1];
  if(hash) {
    $('#TB_iframeContent').one('load', function(){
      var iframeDocument = $(this).contents().get(0);
      var w = iframeDocument.parentWindow || iframeDocument.defaultView;
      if(typeof w == 'object' && typeof w.$ == 'function' && typeof w.$.fn.tabs == 'function') {
        w.$('.tabs').tabs('select', '#'+hash);
      }
    });
  }
});

Drupal.behaviors.os_poker = function(context) {
  /* Hack to fix forgot password iframe in IE7 */
  if ($.browser.msie && $.browser.version === '7.0') {
	  if ($('#forgot_password:not(.os-poker-processed)').addClass('os-poker-processed').length > 0) {
          var href = $('#forgot_password').get(0).href;
          $('#forgot_password').get(0).href = href.replace(/(165)/, '167');
      }
  }

  //A counter incremented for each call of this function
  var call_counter = arguments.callee.call_counter ? arguments.callee.call_counter + 1 : 1;
  arguments.callee.call_counter = call_counter;
  
  if(call_counter === 1 && !$(document.body).hasClass('iframe')) {
      if(typeof os_poker_input_file_style === 'function') {os_poker_input_file_style();}
      if(typeof os_poker_message_start === 'function') {os_poker_message_start();}
      if(typeof os_poker_init_events === 'function') {os_poker_init_events();}
      if(typeof os_poker_init_menu === 'function') {os_poker_init_menu();}
      if(typeof os_poker_init_tourney === 'function') {os_poker_init_tourney();}
      if(typeof os_poker_init_router === 'function') {os_poker_init_router();}
  }
  
  //Register slider handler for the Buddy List's panel
  os_poker_register_slider($('#buddy_panel:not(.os-poker-processed)').addClass('os-poker-processed'));
  
  //Register handler for Buddy List's online filter 
  $("#buddylist #edit-online:not(.os-poker-processed)").addClass('os-poker-processed').click(function () {
    os_poker_filter_online($('#buddy_panel .buddy_list_block'), this.checked);
    os_poker_slider_reset($('#buddy_panel .cursor'));
  });

  //Register handler for Buddy List's "Today Gift" banner (only for first click)
  $('#today_gift:not(.os-poker-processed)').addClass('os-poker-processed').one('click', function(){
    os_poker_send_message({type: 'os_poker_daily_gift'});
  })

  //Display hint in form field
  $('#user_login input[name=name]:not(.os-poker-processed)').addClass('os-poker-processed').focus(function(){
    if($(this).val() === 'E-mail') {
      $(this).val('');
    }
  }).blur(function(){
    if($(this).val() === '') {
      $(this).val('E-mail');
    }
  }).blur();
  $('#user_login input[name=pass]:not(.os-poker-processed)').addClass('os-poker-processed').focus(function(){
    if($(this).val() === 'password') {
      $(this).val('');
    }
  }).blur(function(){
    if($(this).val() === '') {
      $(this).val('password');
    }
  }).blur();

  $('#home_signup_form input[name=pass]').val('');
  
  //Show status message in a thickbox when not on admin pages
  if (!(typeof Drupal.settings.os_poker === 'object' && Drupal.settings.os_poker.inline_messages)) {
    var $messages = $('.messages:not(.os-poker-processed)').addClass('os-poker-processed');
    if ($messages.length) {
      OsPoker.dialog($messages);
    }
  }

  if (typeof $.fn.tabs == 'function') {
    $('#ContainerContentHelp div.tabs:not(.os-poker-processed)').addClass('os-poker-processed').tabs();
  }

};

OsPoker.dialog = function(content) {
  //A counter incremented for each call of this function
  var call_counter = arguments.callee.call_counter ? arguments.callee.call_counter + 1 : 1;
  arguments.callee.call_counter = call_counter;

  var $content = $(content);
  var id = 'messages-popup-' + call_counter;
  //create an hidden container for the thickbox
  var $popup = $content.eq(0).after(Drupal.theme('os_poker_popup', id)).next().hide();
  //move the message into its .content
  $popup.find('.content').append($content).end();
  //register tb_remove as handler for its .close link
  $popup.find('.close').click(tb_remove).end();
  //Reveal the popup (delayed with setTimeout so that any pending javascript is executed before)
  setTimeout(function(){
    tb_show('', '#TB_inline?height=' + $popup.outerHeight() + '&width=' + $popup.outerWidth() + '&inlineId=' + id + '&modal=true', false);
    //Copy the classes from the container to the TB_ajaxContent wrapper
    $("#TB_ajaxContent").addClass($popup.attr('class'));
  }, 0);
}

/**
 * Open a thickbox using content from a inline element.
 *
 * @param id string The ID the element that contains the content you would like
 *   to show in a ThickBox
 * @param options An object A ''map'' of otpions for thickbox (height, width,
 *   modal, etc.)
 **/
OsPoker.inlineThickbox = function(id, options) {
  //Bind the tb_remove function to the click event of any .close element in the
  //thickbexed element. First unbind to avoid multiple binding of the same
  //handler.
  if(typeof options != 'object') {
   options = {modal: options ? true : false};
  }
  var params = $.extend({inlineId: id}, options);
  $('#' + id).find('.close').unbind('click', tb_remove).click(tb_remove).end();
  setTimeout(function(){
    tb_show('', '#TB_inline?' + $.param(params), false);
  }, 0);
}

Drupal.theme.os_poker_link = function() { 
  var base = Drupal.settings.basePath + '?q=';
  if (typeof Drupal.settings.os_poker.language == 'object') {
    base += Drupal.settings.os_poker.language.language + '/';
  }  
  return base + Array.prototype.join.apply(arguments, ['/']);
};

/**
 * Provides markup for the container holding the content of the thickbox for
 * popup message. 
 */
Drupal.theme.os_poker_popup = function(id) {
  return '<div id="'+id+'" class="messages-popup"><a class="close" href="#">close</a><div class="content"></div></div>';
};

Drupal.theme.lobby_player = function(player) {
  var output = '<div class="user">';
  output += '<div class="picture"><a class="thickbox" href="' + Drupal.theme('os_poker_link', 'poker/profile/profile', player.serial, '&height=442&width=603&keepThis=true&TB_iframe=true') + '"><img alt="" src="xsites/default/files/pictures/picture-' + player.serial + '.png"/></a></div>';
  output += '<div class="name"><a class="thickbox" href="' + Drupal.theme('os_poker_link', 'poker/profile/profile', player.serial, '&height=442&width=603&keepThis=true&TB_iframe=true') + '">'+player.name+'</a></div>';
  output += '<div class="money"><a href="#">' + os_poker_number_format(player.chips / 100) + '</a></div>';
  output += '</div>';
  return output;
};
