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
var	_os_poker_mbox_count = 0;

function os_poker_init_messagebox()
{
	var mbox_container = $(".menu-block a[href*=poker/messagebox]");

	mbox_container.append("<span id='mbox_count'></span><span><img id='mbox_pix' src='' alt='Msg' style='display:none;'/></span>");

	os_poker_bind_message("os_poker_messagebox", mbox_container, function(event, arg) {
										
		var pix = event.data.find("#mbox_pix");										
		var count = event.data.find("#mbox_count");						
		
		if (arg.inbox)
		{
			_os_poker_mbox_count = arg.inbox;
			count.text(" " + arg.inbox + " ");
			
			if (arg.picture)
			{
				pix.attr("src", arg.picture);
				pix.show();
			}
			else
			{
				pix.hide();
			}
		}
		else
		{
			pix.hide();
		}
										
	});

	
	//Init Mbox menu
	os_poker_send_message({type :"os_poker_load_messagebox", msgcount: _os_poker_mbox_count});							
}
