/*
 *    Copyright (C) 2009, 2010 Pokermania
 *    Copyright (C) 2010 OutFlop
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
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
