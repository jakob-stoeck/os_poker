function os_poker_init_tourney() {
	os_poker_init_tourney_notify();
	os_poker_bind_message("os_poker_tourney_start", null, function(event, arg) { 
		os_poker_tourney_start_notify(arg.tourney_name, arg.table_id, true);
	});
}

function os_poker_init_tourney_notify() {
  var tourney_notify_tpl = $("#tourney-notify-template");
  tourney_notify_tpl.after($('<div id="tourney-notify"><a href="#" class="close-button"></a><div class="notify-text" align="center">' +
	tourney_notify_tpl.html() + '</div></div>'));
  $("#tourney-notify").hide();
  tourney_notify_tpl.remove();
  
  $("#tourney-notify .close-button").click(function(e) {$("#tourney-notify").hide();});
        // tourney-notify test
        $("p.buddy_count").click(function(e) {
                os_poker_tourney_start_notify("Test", 123, true);
        });

  if (window.top.tourney_notify) {
    os_poker_tourney_start_notify(window.top.tourney_notify.name, window.top.tourney_notify.table_id, true);
  }
}

function os_poker_tourney_start_notify(tourney_name, table_id, flag_in_progress) 
{
	var tUrl = os_poker_site_root() + "?view=table&game_id=" + table_id;
	$("#tourney-notify .notify-text span").html("<a href='" + tUrl + "'>" + tourney_name + '/' + table_id + '</a>');
	$("#tourney-notify").fadeIn('slow');
	
}
