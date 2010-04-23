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
$(document).ready(function () {
	os_poker_input_file_style();
	os_poker_message_start(false);
	os_poker_init_events();
	os_poker_init_pager();
	
	os_poker_register_slider($("#item_panel"));
});

Drupal.behaviors.os_poker_init_messagelist = function() {
	$('#message-list .inner-item-list a:not(.os-poker-messagelist-processed)').addClass('os-poker-messagelist-processed').each(function() {
	
		if ($(this).hasClass("user_relationships_popup_link") === false && $(this).hasClass("noreplace") === false)
		{
			var tg = $(this).attr("href");
			
			$(this).attr("href", "javascript:void(0);");
			$(this).click(function() { parent.os_poker_trigger("os_poker_jump", {url:tg + "&height=442&width=603", lightbox:true}); });
		}
	});

}

