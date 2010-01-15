
function		os_poker_init_events()
{
	os_poker_bind_message("os_poker_jump", null,  function(event, arg) {

										if (typeof(arg.url) != "undefined")
										{
											if (arg.lightbox == true)
											{	
												if (!arg.url.match(/TB_iframe/))
												{
													arg.url += (((arg.url.match(/\?/)) ? "&" : "?") + "TB_iframe=true");
												}
											
												tb_show(null, arg.url, false);
											}
											else
											{
												document.location.href = arg.url;
											}
										}
								});

	os_poker_bind_message("os_poker_update_chips", $("b.chips"),  function(event, arg) {
	
										if (typeof(arg.amount) != "undefined")
										{
                      if(!isNaN(parseInt(arg.amount))) {
                        arg.amount = os_poker_number_format(arg.amount);
                      }
											event.data.text(arg.amount);
										}
								});
								
}