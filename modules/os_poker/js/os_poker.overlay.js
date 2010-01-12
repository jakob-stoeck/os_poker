Drupal.behaviors.os_poker_overlay = function(context) {
  var $overlay = $('#os-poker-overlay:not(.os-poker-overlay-processed)', context).addClass('os-poker-overlay-processed');
  if($overlay.length > 0) {
    var $content = $overlay.find('.content');
    var $floater = $overlay.find('.floater');
    var height = $content.outerHeight();
    $floater.css('margin-bottom', -Math.ceil(height/2) + 'px');
    //$content.css('height', height + 'px');
  }
}