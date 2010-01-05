Drupal.theme.os_poker_popup = function(id) {
  return '<div id="'+id+'" class="messages-popup"><a class="close" href="#">close</a><div class="content"></div><a class="button close">'+Drupal.t('OK')+'</a></div>';
}

Drupal.behaviors.poker = function(){
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