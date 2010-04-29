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
/*
** OS Poker message API
** Require JQuery
*/


var 	_os_poker_timeout = 120000; /* Timeout = 2mn */
var		_os_poker_debug = false;
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
  if (messages.error && window.console && window.console.log)   {
     window.console.log('Received error: ' + messages.errorMsg);
  }
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

function	os_poker_send_message(js_args)
{
	var args = {};
	$.each(js_args, function(key,val) {
		if ( typeof(key) != 'function' && key != 'type') {
			args[key] = JSON.stringify(val);
		} else {
			args[key] = val;
		}
	});
	args.ajax = 1;

	_os_poker_send_handler = $.ajax({
            type: "GET",
            url: _os_poker_message_url + "/send",
            async: true,
            cache: false,
            timeout: _os_poker_timeout,
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
      }
	});
}

/*
**
*/

function	os_poker_message_debug()
{
	$.ajax({
            type: "GET",
            url: _os_poker_message_url + "/receive&debug=1",
            async: true,
            cache: false,
            timeout: _os_poker_timeout + 20000,
			dataType: "json",
			data: {ajax: 1},

      success: function(responseObject)
			{
				setTimeout(os_poker_message_debug, 6000);
			},
			
      error: function(XMLHttpRequest, textStatus, errorThrown)
			{
				if (_os_poker_debug)
				{
					console.warn("Error in message receive, restarting ....");
				}
			}
	});
}


function	os_poker_message_listen()
{
	_os_poker_listen_handler = $.ajax({
            type: "GET",
            url: '/POLL',
            async: true,
            cache: false,
            timeout: _os_poker_timeout,
			dataType: "json",
	    data: {ajax: 1},

      success: function(responseObject)
			{
				os_poker_process_message(responseObject);
				
				_os_poker_timer_handler = setTimeout(os_poker_message_listen, 6000);
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
			}
	});
}

/*
**
*/

function	os_poker_message_shutdown()
{
	if (_os_poker_debug)
	{
		console.info("Message API Shutdown");
	}

	if (_os_poker_listen_handler)
	{
		if (_os_poker_debug) {
			console.info("> Message Poller Stop ...");
		}
		_os_poker_listen_handler.abort();
		_os_poker_listen_handler = null;
	}
	else if (_os_poker_debug)
	{
		console.warn("> No running message poller.");
	}
	
	if (_os_poker_send_handler)
	{
		if (_os_poker_debug) {
			console.info("> Message Send Stop ...");
		}
		_os_poker_send_handler.abort();
		_os_poker_send_handler = null;
	}
	else if (_os_poker_debug)
	{
		console.warn("> No message in send.");
	}
	
	if (_os_poker_timer_handler)
	{
		if (_os_poker_debug) {
			console.info("> Timer Stop ...");
		}
		clearTimeout(_os_poker_timer_handler);
		_os_poker_timer_handler = null;
	}
	else if (_os_poker_debug)
	{
		console.warn("> No timer to stop.");
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
	
	_os_poker_message_url = os_poker_site_root();
	
	if (_os_poker_message_url.match(/\?/)) {
	    _os_poker_message_url += "/poker/messages";
	} else {
	    _os_poker_message_url += "?q=poker/messages";
	}
	
	os_poker_bind_message("os_poker_stop_poll", null, function(event, arg) { os_poker_message_shutdown(); });
	
	if (typeof(listen) != "boolean" && listen !== false)
	    {
		os_poker_message_listen();
		//		os_poker_message_debug();
	    }
}

