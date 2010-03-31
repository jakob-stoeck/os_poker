
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