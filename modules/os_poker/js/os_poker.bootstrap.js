var OsPoker = OsPoker || {};

/**
 * Drop call of tb_init('a.thickbox, area.thickbox, input.thickbox') on page load
 * in any nested (i)frame. Yes this is an ugly hack. But its needed since
 * thickbox is so unflexible.
 */
if(window != window.top) {
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

Drupal.behaviors.os_poker = function(context) {
  //A counter incremented for each call of this function
  var call_counter = arguments.callee.call_counter ? arguments.callee.nextCpt++ : 0;
  arguments.callee.call_counter = call_counter;
  
  if(call_counter === 0 && !$(document.body).hasClass('page-poker')) {
      if(typeof os_poker_input_file_style === 'function') {os_poker_input_file_style();}
      if(typeof os_poker_message_start === 'function') {os_poker_message_start();}
      if(typeof os_poker_init_events === 'function') {os_poker_init_events();}
      if(typeof os_poker_init_menu === 'function') {os_poker_init_menu();}
  }
  
  //Register slider handler for the Buddy List's panel
  os_poker_register_slider($('#buddy_panel:not(.os-poker-processed)').addClass('os-poker-processed'));
  
  //Register handler for Buddy List's online filter 
  $("#buddylist #edit-online:not(.os-poker-processed)").addClass('os-poker-processed').click(function () {
    os_poker_filter_online($('#buddy_panel .buddy_list_block'), this.checked);
    os_poker_slider_reset($('#buddy_panel .cursor'));
  });

  //Register handler for Buddy List's "Today Gift" banner
  $('#today_gift').click(function(){
    os_poker_send_message({type: 'os_poker_daily_gift'});
  })

  //Display hint in form field
  $('#user_login input[name=name]').focus(function(){
    if($(this).val() === 'E-mail') {
      $(this).val('');
    }
  }).blur(function(){
    if($(this).val() === '') {
      $(this).val('E-mail');
    }
  }).blur();
  $('#user_login input[name=pass]').focus(function(){
    if($(this).val() === 'password') {
      $(this).val('');
    }
  }).blur(function(){
    if($(this).val() === '') {
      $(this).val('password');
    }
  }).blur();

  //Show status message in a thickbox when not on admin pages
  if (!(typeof Drupal.settings.os_poker === 'object' && Drupal.settings.os_poker.inline_messages)) {
    var $messages = $('.messages:not(.os-poker-processed)').addClass('os-poker-processed');
    if ($messages.length) {
      OsPoker.dialog($messages);
    }
  }

  if (typeof $.fn.tabs == 'function') {
    $('#ContainerContentHelp .tabs').tabs();
  }
};

OsPoker.dialog = function(content) {
  //A counter incremented for each call of this function
  var call_counter = arguments.callee.call_counter ? arguments.callee.nextCpt++ : 0;
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

Drupal.theme.os_poker_link = function() {
  return Drupal.settings.basePath + '?q=' + Array.prototype.join.apply(arguments, ['/']);
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