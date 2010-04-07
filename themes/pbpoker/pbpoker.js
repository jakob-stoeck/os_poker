Drupal.behaviors.pbPoker = function(context) {
  if($(document.body).hasClass('front')) {
    var openRegistrationForm = function(event){
      if(event) event.preventDefault();
      OsPoker.inlineThickbox('middle-content-right', {width: 410, height: 420});
    };
    $('a.open-register-form:not(.pb-poker-processed)').addClass('pb-poker-processed').click(openRegistrationForm);
    if((Drupal.settings.os_poker.invite) || window.location.hash == '#registration-window' || $('#middle-content-right .messages.error', context).length) {
      openRegistrationForm();
    }
  }
}
