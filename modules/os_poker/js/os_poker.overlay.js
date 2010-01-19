Drupal.behaviors.os_poker_overlay = function(context) {
  var $overlay = $('#os-poker-overlay:not(.os-poker-overlay-processed)', context).addClass('os-poker-overlay-processed');
  if($overlay.length > 0) {
    $('html').css('overflow', 'hidden');
    var $content = $overlay.find('.content');
    var $floater = $overlay.find('.floater');
    var height = $content.outerHeight();
    $floater.css('margin-bottom', -Math.ceil(height/2) + 'px');

    var close = function() {
      $overlay.hide();
      $('#os-poker-overlay-mask').hide();
      $('html').css('overflow', '');
    }
    $overlay.bind('click', function(event){
      var $target = $(event.target);
      if($target.hasClass('close')) {
        close();
        return false;
      }
      return true;
    });
    //Opening a thickbox should close the overlay.
    //Since thickbox stop event bubbling, the previous bind will not catch click events on element triggering a thickbox.
    $overlay.find('a.thickbox, area.thickbox, input.thickbox').bind('click', function(event){
      close();
    });
  }
}