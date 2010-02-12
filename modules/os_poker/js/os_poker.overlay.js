Drupal.behaviors.os_poker_overlay = function(context) {
  var $overlay = $('#os-poker-overlay:not(.os-poker-overlay-processed)', context).addClass('os-poker-overlay-processed');
  if($overlay.length > 0) {
    $('html').css('overflow', 'hidden');
    var $content = $overlay.find('.content');
    var $floater = $overlay.find('.floater');
    var $mask = $('#os-poker-overlay-mask');
    var height = $content.outerHeight();
    $floater.css('margin-bottom', -Math.ceil(height/2) + 'px');
    $floater.height($floater.height()); // <- this fix the element height in IE... die IE ! die !
    var close = function() {
      $overlay.hide();
      $mask.hide();
      $('html').css('overflow', '');
      return false;
    };
    $overlay.bind('click', function(event){
      if($(event.target).hasClass('close')) {
        return close();
      }
      return true;
    });
    //Opening a thickbox should close the overlay.
    //Since thickbox stop event bubbling, the previous bind will not catch click events on element triggering a thickbox.
    $overlay.find('a.thickbox, area.thickbox, input.thickbox').bind('click', function(event){
      return close();
    });

  }
};
