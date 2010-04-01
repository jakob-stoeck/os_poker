Drupal.behaviors.pbPoker = function(context) {
  if($(document.body).hasClass('front')) {
    if($(document.body).hasClass('not-logged-in')) {
      var openRegistrationForm = function(event){
        if(event) event.preventDefault();
        OsPoker.inlineThickbox('middle-content-right', {width: 410, height: 420});
      };
      $('a.open-register-form:not(.pb-poker-processed)').addClass('pb-poker-processed').click(openRegistrationForm);
      if((Drupal.settings.os_poker.invite) || $('#middle-content-right .messages.error', context).length) {
        openRegistrationForm();
      }
    }

    //Handle click on the video tutorial link
    $('a.open-video-tutorial:not(.pb-poker-processed)').addClass('pb-poker-processed').click(function(){
      //Wrap in a setTimeout to ensure thickbox handles the click before we do anything
      setTimeout(function(){
        //Once the thickbox iframe content is loaded, select the tutorial tab
        $('#TB_iframeContent').one('load', function(){
          var iframeDocument = $(this).contents().get(0);
          var w = iframeDocument.parentWindow || iframeDocument.defaultView;
          if(typeof w == 'object' && typeof w.$ == 'function') {
            w.$('.tabs').tabs('select', 3);
          }
        })
      }, 0);
    });
  }
}
