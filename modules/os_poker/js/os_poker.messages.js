/*
** OS Poker message API
** Require JQuery
*/


const 	_os_poker_timeout = 120000; /* Timeout = 2mn */
var		_os_poker_debug = true;
var 	_os_poker_message_url;
var 	_os_poker_send_handler = null;
var 	_os_poker_listen_handler = null;
var 	_os_poker_timer_handler = null;

/*
**
*/

function	os_poker_bind_message(eventName, elem, callback)
{
	$(document).bind(eventName, elem, callback);
}

/*
**
*/

function	os_poker_trigger(eventName, args)
{
	$(document).trigger(eventName, args);
}

/*
**
*/

function	os_poker_process_message(messages)
{
	var msg = messages.messages;

	for (i=0; i < msg.length; i++)
	{
		if (msg[i] && msg[i].type != "noop")
		{
			$(document).trigger(msg[i].type, msg[i].body);
		}
	}

}

/*
**
*/

function	os_poker_send_message(args)
{
	args.ajax = 1;

	_os_poker_send_handler = $.ajax({
            type: "GET",
            url: _os_poker_message_url + "/send",
            async: true,
            cache: false,
			dataType: "json",
			data: args,

            success: function(responseObject)
			{
				os_poker_process_message(responseObject);
				_os_poker_send_handler = null;
			},
			
            error: function(XMLHttpRequest, textStatus, errorThrown)
			{
				if (_os_poker_debug)
				{
					console.warn("Failed to send message");
				}
				_os_poker_send_handler = null;
            },
	});
}

/*
**
*/

function	os_poker_message_listen()
{
	_os_poker_listen_handler = $.ajax({
            type: "GET",
            url: _os_poker_message_url + "/receive",
            async: true,
            cache: false,
            timeout: _os_poker_timeout,
			dataType: "json",
			data: {ajax: 1},

            success: function(responseObject)
			{
				os_poker_process_message(responseObject);
				
				_os_poker_timer_handler = setTimeout('os_poker_message_listen()', 3000);
				_os_poker_listen_handler = null;
			},
			
            error: function(XMLHttpRequest, textStatus, errorThrown)
			{
				if (_os_poker_debug)
				{
					console.warn("Error in message receive, restarting ....");
				}

				_os_poker_listen_handler = null;
				
				//_os_poker_timer_handler = setTimeout('os_poker_message_listen()', 6000);
            },
	});
}

/*
**
*/

function	os_poker_message_shutdown()
{
	if (_os_poker_timer_handler)
	{
		clearTimeout(_os_poker_timer_handler);
	}
	
	if (_os_poker_listen_handler)
	{
		_os_poker_listen_handler.abort();
		_os_poker_listen_handler = null;
	}
	
	if (_os_poker_send_handler)
	{
		_os_poker_send_handler.abort();
		_os_poker_send_handler = null;
	}

}

function	os_poker_message_start(listen)
{
	try
	{
		if (_os_poker_debug)
		{
			console.info("Message API Initialisation");
		}
    } 
	catch (e)
	{
		_os_poker_debug = false;
	}
	
	

	_os_poker_message_url = os_poker_site_root()
	
	if (_os_poker_message_url.match(/\?/)) _os_poker_message_url += "/poker/messages";
	else _os_poker_message_url += "?q=poker/messages";
	
	if (typeof(listen) != "boolean" && listen != false)
		os_poker_message_listen();
}

