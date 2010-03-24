Drupal.behaviors.pbPoker = function(context) {
  if($(document.body).hasClass('front')) {
    var openRegistrationForm = function(event){
      if(event) event.preventDefault();
      OsPoker.inlineThickbox('middle-content-right');
    };
    $('a.open-register-form:not(.pb-poker-processed)').addClass('pb-poker-processed').click(openRegistrationForm);
    if((Drupal.settings.os_poker.invite) || $('#middle-content-right #messages .messages', context).length) {
      openRegistrationForm();
    }
  }
}
