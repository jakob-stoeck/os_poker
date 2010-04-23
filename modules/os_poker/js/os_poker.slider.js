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
function	os_poker_slider_reset(elem)
{
	elem.css("position", "absolute");
	elem.css("left", "0px");
	var vis = 0;
	var children = elem.children(":visible").each(function(){
    vis += $(this).outerWidth();
  });
  elem.css("width", vis + "px");
}

/*
**
*/

function	os_poker_slider_get_page(elem)
{
	var cursor = elem.find(".cursor");
	
	var pos = parseInt(cursor.css("left"), 10);
	var children = cursor.children();
	var fc = children.get(0);
	var step = $(fc).outerWidth();
	var index = -pos / step;
	return children.get(index);
}

/*
**
*/

function	os_poker_register_slider(elem)
{
	var next = elem.find(".next");
	var previous = elem.find(".previous");
	var cursor = elem.find(".cursor");
	
	os_poker_slider_reset(cursor);
	
	next.bind("click", cursor, function(event) {
		var pos = parseInt(event.data.css("left"), 10);
		var children = event.data.children(":visible");
		var fc = children.get(0);
		var step = $(fc).outerWidth( );
		var vis = children.length * step;
		var nextpos = pos - step;
		var max = event.data.parent().width();
		
		event.data.css("width", vis + "px");
		
		if (vis + nextpos >= max)
		{
			event.data.css("left", nextpos + "px");
		}
		return false;
	});	
	
	previous.bind("click", cursor, function(event) {
		var pos = parseInt(event.data.css("left"), 10);
		var children = event.data.children(":visible");
		var fc = children.get(0);
		var step = $(fc).outerWidth( );
		var nextpos = pos + step;
		
		if (nextpos <= 0)
		{
			event.data.css("left", nextpos + "px");
		}
		return false;
	});

}
