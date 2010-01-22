
function		os_poker_init_events()
{
  os_poker_bind_message('os_poker_table_selected', null, function(event, arg) {
    if(!isNaN(parseInt(arg.table))) {
      $('#table_users .list').load('?q='+Drupal.encodeURIComponent('poker/table/'+arg.table+'/players'), {table: arg.table.toString()}, function(responseText, textStatus, XMLHttpRequest){
        if(textStatus === 'success' && $(this).find('.user').length > 0) {
          $('#table_users .header').show();
          $('#table_users .list').removeClass('splash');
        }
        else {
          $('#table_users .header').hide();
          $('#table_users .list').addClass('splash');
        }

      });
    }
  });

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