
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
	document.location = os_poker_site_root() + '?view=table&game_id=' + game_id;
}

function	os_poker_goto_lobby()
{
	document.location = os_poker_site_root();
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
	if (x != 0)
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
	})
	
	$("#shop #buy_form_item").val(id_item);
}

function	os_poker_setup_shop_buy(submit_type, elem)
{
	if (!elem.hasClass("disabled"))
	{
		$("#shop #buy_form_action").val(submit_type);
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
			
			user_list += "<div class='user'>";
			user_list += '<div class="picture"><a href="?q=user/' + user.serial + '"><img alt="" src="sites/default/files/pictures/picture-' + user.serial + '.png" onError="javascript:os_poker_default_image($(this));"/></a></div>';
			user_list += '<div class="name"><a href="?q=user/' + user.serial + '">' +  user.name + '</a></div>';
			user_list += '<div class="money"><a href="#">' + os_poker_number_format(user.chips / 100) + '</a></div>';
			user_list += "</div>";
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
		document.location.href = os_poker_site_root() + "?&view=table&challenge=join";
	});
	
}

/*
**
*/

function	os_poker_activate_item(elem)
{
	os_poker_send_message({type:"os_poker_activate_item", id_item:elem.id});
	$(elem).parent().children().removeClass("active");
	$(elem).addClass('active');
}

/*
**
*/

function	os_poker_show_medium_profile(user_id, game_id)
{
	var tUrl = os_poker_site_root() + "?q=poker/profile/medium/" + user_id;
	
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

/*
**
*/

function os_poker_switch_today_gift_blocks()
{
	  $('#today_gift').hide();
	  $('#today_gift_invite').show();
}

