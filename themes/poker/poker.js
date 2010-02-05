Drupal.theme.os_poker_popup = function(id) {
  return '<div id="'+id+'" class="messages-popup"><a class="close" href="#">close</a><div class="content"></div>'+Drupal.theme('os_poker_button', Drupal.t('close'), 'close')+'</div>';

}

Drupal.theme.os_poker_button = function(label, cls) {
  var output = '<div class="poker_submit';
  if (typeof cls != 'undefined') {
    output += ' ';
    output += cls;
  }
  output += '">';
  output += '<div class="pre">&nbsp;</div>';
  output += '<div style="width: 60px; text-align: center;" class="label">';
  output += label;
  output += '</div>';
  output += '<div class="user_login_clear"></div>';
  output += '</div>';
  return output;
}

Drupal.behaviors.poker = function(){
  if(window.parent != window) {
    $('html').addClass('framed');
  }

  //Forward click on button wrapper to the wrapped button itslef
  $('.poker_submit.form-submit:not(.poker-processed)')
    .addClass('poker-processed')
    .click(function(event){
      //Filter button click
      if(typeof event.target.type !== 'string' && event.target.type !== 'submit') {
        //Forward the click to the (first) contained button
        $(this).find('input[type=submit]').eq(0).click();
        return false;
      }
      return true;
    });
}
