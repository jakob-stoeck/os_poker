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
function	os_poker_site_root()
{
	if (Drupal.jsEnabled)
	{
		return Drupal.settings.basePath;
	}
	return "/";
}

/*
**
*/
	
function	os_poker_goto_table(game_id)
{
  var location = os_poker_site_root() + '?view=table&game_id=' + game_id;
  if (typeof Drupal.settings.os_poker.language == 'object') {
    location += '&q=' + Drupal.settings.os_poker.language.language;
  }
	document.location = location;
}

function	os_poker_goto_lobby()
{
  var location = os_poker_site_root();
  if (typeof Drupal.settings.os_poker.language == 'object') {
    location += '?q=' + Drupal.settings.os_poker.language.language;
  }
	document.location = location;
}

/*
**
*/

function	os_poker_submit(link, form_id, ajax, closetb)
{
	var jlink = $(link);
	
	if (jlink.length == 1)
	{
		var tform = $('form#' + form_id);
		
		if (tform.length == 1)
		{
			if (typeof(ajax) != "undefined" && ajax)
			{
				var params = tform.serializeArray();
				var url = tform.attr("action");
				
				$.post(url, params, function() {
					if (typeof(closetb) != "undefined" && closetb)
					{
						parent.tb_remove();
					}
				});
			}
			else
			{
				tform.submit();
			}
		}
	}
}

/*
**
*/

function	os_poker_number_format(x)
{
	if (x !== 0)
	{
		var str = x.toString(), n = str.length;

		if (n > 3)
		{
			x = ((n % 3) ? str.substr(0, n % 3) + ',' : '') + str.substr(n % 3).match(new RegExp('[0-9]{3}', 'g')).join(',');
		}
	}
	return "$ " + x;
}

function	os_poker_setup_shop_item_select(elem, price, id_item)
{
	$("#shop #items a.selected").removeClass( "selected" );
	elem.addClass( "selected" );
	
	$("#shop div.poker_submit.big.disabled").removeClass( "disabled" );
	
	$("#shop .total").each(function () {
		var mult = $(this).attr("mul");
		$(this).text(os_poker_number_format(price * mult));
	});
	
	$("#shop #buy_form_item").val(id_item);
}

function	os_poker_setup_shop_buy(submit_type, elem)
{
	if (!elem.hasClass("disabled"))
	{
		$("#shop #buy_form_action").val(submit_type);
    $("#shop #buy_form_activate").val(elem.hasClass("activate-item") ? 1 : 0);
		$("#shop form#buy_form").submit();
	}
}

/*
**
*/

function	os_poker_default_image(elem)
{
	//elem.attr("src", "sites/all/themes/poker/images/picture-default.png");
}

/*
**
*/

function	os_poker_sit(players_at_table)
{
	os_poker_send_message({type :"os_poker_sit_down", players: players_at_table});
}

/*
**
*/

function	os_poker_update_lobby(users)
{
	var user_list = "";
  var lobby = $('#table_users');
	var container = lobby.find('.list');
	var innerContainer = lobby.find('.userlist');
			
	if (users && users.length > 0)
	{
		container.removeClass('splash');
    lobby.find('.header').show();
		
		for (place in users)
		{
			var user = users[place];
			user_list += Drupal.theme('lobby_player', user);
		}
		
		innerContainer.html(user_list);
		return;
	}
	
	innerContainer.html("");
	container.addClass('splash');
  lobby.find('.header').hide();
}

/*
**
*/

function	os_poker_clear_selection()
{  
	if(document.selection && document.selection.empty)
	{
		document.selection.empty();
	}
	else if (window.getSelection)
	{
		var sel = window.getSelection();
		sel.removeAllRanges();
	}
}

/*
**
*/

function	os_poker_filter_online(elem, status)
{
	for (var i = 0; i < elem.length; ++i)
	{
		var e = $(elem[i]);
		
		if (!status || e.hasClass("online"))
		{
			e.show();
		}
		else
		{
			e.hide();
		}
	}
}

/*
**
*/

function	os_poker_start_challenge(player1, player2)
{
	$.get(os_poker_site_root(), {view: "table", challenge: player1 +","+player2}, function() {
    var location = os_poker_site_root() + "?&view=table&challenge=join";
    if (typeof Drupal.settings.os_poker.language == 'object') {
      location += '&q=' + Drupal.settings.os_poker.language.language;
    }
		document.location.href = location;
	});
	
}

/*
**
*/

function	os_poker_activate_item(elem)
{
	os_poker_send_message({type:"os_poker_activate_item", id_item:parseInt(elem.id)});
	$(elem).parent().children().removeClass("active");
	$(elem).addClass('active');
  if(typeof window.top.tb_remove === 'function') {
    window.top.tb_remove();
  }
}

/*
**
*/

function	os_poker_show_medium_profile(user_id, game_id)
{
  var tUrl;
  if (typeof Drupal.settings.os_poker.language == 'object') {
  	tUrl = os_poker_site_root() + "?q=" + Drupal.settings.os_poker.language.language + "/poker/profile/medium/" + user_id;
	}
	else {
	  tUrl = os_poker_site_root() + "?q=poker/profile/medium/" + user_id;
	}
	
	if (typeof(game_id) != "undefined")
	{
		tUrl += "/" + game_id;
	}
	
	tUrl += "/&height=294&width=291";

	os_poker_trigger("os_poker_jump", {url: tUrl, lightbox:true});
}

/*
**
*/

function os_poker_play_now_clicked()
{

}

/**
 * Router invokes JS functions based on the # anchor in the url.
 * if the anchor is #load_xyz, it will invoke a function window.top.os_poker_load_xyz() if such
 * a function exists.
 */ 
function os_poker_init_router() 
{
	if (window.top.location.href.match(/#(load_.+)/) ||
		window.top.location.href.match(/initcall_([a-z0-9A-Z_]+)/)) {
                var route = 'os_poker_' + RegExp.$1;
                if (window.top[route] && typeof(window.top[route]) == 'function') {
                        setTimeout(function() {
                                window.top[route]();
                        }, 1000);
 		}
	}

	if (window.location.href.match(/#(load_.+)/) ||
                window.location.href.match(/initcall_([a-z0-9A-Z_]+)/)) {
		var route = 'os_poker_' + RegExp.$1;
		if (window[route] && typeof(window[route]) == 'function') {
			setTimeout(function() {
				window[route]();
			}, 1000);
        	}
	}
}

function os_poker_load_tutorial() {
	$("a.tutorial").trigger('click');
}

