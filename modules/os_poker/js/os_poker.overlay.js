Drupal.behaviors.os_poker_overlay = function(context) {
  var $overlay = $('#os-poker-overlay:not(.os-poker-overlay-processed)', context).addClass('os-poker-overlay-processed');
  if($overlay.length > 0) {
    $('html').css('overflow', 'hidden');
    var $content = $overlay.find('.content');
    var $floater = $overlay.find('.floater');
    var height = $content.outerHeight();
    $floater.css('margin-bottom', -Math.ceil(height/2) + 'px');
    $overlay.bind('click', function(event){
      if($(event.target).hasClass('close')) {
        $(this).hide();
        $('#os-poker-overlay-mask').hide();
        $('html').css('overflow', '');
        return false;
      }
      return true;
    });
  }
}