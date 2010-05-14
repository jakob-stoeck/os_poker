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
/*
 * This script is included in the main window when the user receive the daily bonus (at login, once every day)
 */ 

Drupal.behaviors.dailygiftBehavior = function() {
  // Trigger invite friends thickbox instead of dailychips confirmation
  setTimeout(function() {
      var bl = $(".buddy_list_placeholder a.thickbox");
      if (bl.length > 0) {
          bl.eq(0).trigger('click');
      } 
  }, 1000);
  //Avoid running this behavior more than once
  delete Drupal.behaviors.dailygiftBehavior;
}


