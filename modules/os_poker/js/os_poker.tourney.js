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
function os_poker_init_tourney() {
	os_poker_init_tourney_notify();
	os_poker_bind_message("os_poker_tourney_start", null, function(event, arg) {
		os_poker_tourney_start_notify(arg.tourney_name, arg.table_id, true);
	});
}

function os_poker_init_tourney_notify() {
	var tourney_notify_tpl = $("#tourney-notify-template");
	tourney_notify_tpl
			.after($('<div id="tourney-notify"><a href="#" class="close-button"></a><div class="notify-text" align="center">' + tourney_notify_tpl
					.html() + '</div></div>'));
	$("#tourney-notify").hide();
	tourney_notify_tpl.remove();

	$("#tourney-notify .close-button").click(function(e) {
		$("#tourney-notify").hide();
	});
	// tourney-notify test
	$("p.buddy_count").click(function(e) {
		os_poker_tourney_start_notify("Test", 123, true);
	});
	var topFrame = os_poker_get_top_window(window);
	
	if (topFrame.tourney_notify) {
		os_poker_tourney_start_notify(topFrame.tourney_notify.name,
				topFrame.tourney_notify.table_id, true);
				
	}
}


function os_poker_tourney_start_notify(tourney_name, table_id, flag_in_progress) {
	var tUrl = os_poker_site_root() + "?view=table&game_id=" + table_id;
	$("#tourney-notify .notify-text span").html(
			"<a href='" + tUrl + "'>" + tourney_name + '/' + table_id + '</a>');
	$("#tourney-notify").fadeIn('slow');

}
