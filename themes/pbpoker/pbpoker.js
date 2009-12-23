Drupal.behaviors.pbpoker = function(context) {
  var nextId = function() {
    arguments.callee.nextId = arguments.callee.nextId ? arguments.callee.nextId++ : 0;
    return arguments.callee.nextId;
  }
  if (!$(document.body).hasClass('page-admin')) {
    var $messages = $('.messages.status', context);
    if ($messages.length) {
      var id = 'messages-popup-' + nextId();
      //create an hidden container for the popup
      var $popup = $messages.after(Drupal.theme('pbpoker_popup', id)).next().hide();
      //move the message into its .content
      $popup.find('.content').append($messages).end()
      //register tb_remove as handler for its .close link
      $popup.find('.close').click(tb_remove).end();
      //Reveal the popup (delayed with setTimeout so that any pending javascript is executed before)
      setTimeout(function(){
        tb_show('', '#TB_inline?height=' + $popup.height() + '&width=' + $popup.width() + '&inlineId=' + id + '&modal=true', false);
        $("#TB_ajaxContent").addClass($popup.attr('class'));
      }, 0);
    }
  }
}

Drupal.theme.pbpoker_popup = function(id) {
  return '<div id="'+id+'" class="messages-popup"><a class="close" href="#">close</a><div class="content"></div></div>';
}
