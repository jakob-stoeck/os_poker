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
function	os_poker_init_pager(target_item)
{
	var elems;

	if (typeof(target_item) != "undefined")
	{
		elems = target_item.find(".ajax-pager .pager a");
	}
	else
	{
		elems = $(".ajax-pager .pager a");
	}
	
	elems.each(function() {
		var target = $(this).attr("href");
		var container = $(this).closest(".ajax-pager").parent();
		
		$(this).attr("href", "javascript:void(0);");
		
		$(this).click(function () {
			$.get(target, function (data) { 
				container.html(data);
				os_poker_init_pager(container);
			});
		});
	});
	
}

