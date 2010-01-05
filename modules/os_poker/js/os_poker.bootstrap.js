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
    }
  })(jQuery);
}

Drupal.behaviors.os_poker = function(context) {
  //A counter incremented for each call of this function
  var call_counter= arguments.callee.call_counter = arguments.callee.call_counter ? arguments.callee.nextCpt++ : 0;
  
  if(call_counter == 0 && !$(document.body).hasClass('page-poker')) {
    if(typeof os_poker_input_file_style === 'function') os_poker_input_file_style();
    if(typeof os_poker_message_start === 'function') os_poker_message_start();
    if(typeof os_poker_init_events === 'function') os_poker_init_events();
    if(typeof os_poker_init_menu === 'function') os_poker_init_menu();
  }
  
  //Register slider handler for the Buddy List's panel
  os_poker_register_slider($('#buddy_panel:not(.os-poker-processed)').addClass('os-poker-processed'));
  
  //Register handler for Buddy List's online filter 
  $("#buddylist #edit-online:not(.os-poker-processed)").addClass('os-poker-processed').click(function () {
    os_poker_filter_online($('#buddy_panel .buddy_list_block'), this.checked);
    os_poker_slider_reset($('#buddy_panel .cursor'));
  });
  
  //Show status message in a thickbox when not on admin pages
  if (!(typeof Drupal.settings.os_poker === 'object' && Drupal.settings.os_poker.inline_messages)) {
    var $messages = $('.messages:not(.os-poker-processed)').addClass('os-poker-processed');
    if ($messages.length) {
      var id = 'messages-popup-' + call_counter;
      //create an hidden container for the thickbox
      var $popup = $messages.eq(0).after(Drupal.theme('os_poker_popup', id)).next().hide();
      //move the message into its .content
      $popup.find('.content').append($messages).end()
      //register tb_remove as handler for its .close link
      $popup.find('.close').click(tb_remove).end();
      //Reveal the popup (delayed with setTimeout so that any pending javascript is executed before)
      setTimeout(function(){
        tb_show('', '#TB_inline?height=' + $popup.outerHeight() + '&width=' + $popup.outerWidth() + '&inlineId=' + id + '&modal=true', false);
        //Copy the classes from the container to the TB_ajaxContent wrapper
        $("#TB_ajaxContent").addClass($popup.attr('class'));
      }, 0);
    }
  }
}

/**
 * Provides markup for the holding the content of the thickbox content for popup message. 
 */
Drupal.theme.os_poker_popup = function(id) {
  return '<div id="'+id+'" class="messages-popup"><a class="close" href="#">close</a><div class="content"></div></div>';
}