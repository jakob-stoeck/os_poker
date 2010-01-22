var OsPoker = OsPoker || {};

OsPoker.eventHandlers = {
  os_poker_table_selected: function(event, arg) {
    if(!isNaN(parseInt(arg.table, 10))) {
      $('#table_users .list').load(Drupal.settings.basePath+'?q='+Drupal.encodeURIComponent('poker/table/'+arg.table.toString()+'/players'), function(responseText, textStatus, XMLHttpRequest){
        if(textStatus === 'success' && $(this).find('.user').length > 0) {
          $('#table_users .header').show();
          $('#table_users .list').removeClass('splash');
          Drupal.attachBehaviors(this);
          tb_init(this);
        }
        else {
          $(this).html('');
          $('#table_users .header').hide();
          $('#table_users .list').addClass('splash');
        }
      });
    }
  },
  os_poker_jump: function(event, arg) {
  if (typeof(arg.url) != "undefined") {
      if (arg.lightbox == true) {
        if (!arg.url.match(/TB_iframe/)) {
          arg.url += (((arg.url.match(/\?/)) ? "&" : "?") + "TB_iframe=true");
        }
        tb_show(null, arg.url, false);
      }
      else {
        document.location.href = arg.url;
      }
    }
  },
  os_poker_update_chips: {
    selector:  'b.chips',
    fn: function(event, arg) {
      if (typeof(arg.amount) != "undefined") {
        if(!isNaN(parseInt(arg.amount))) {
          arg.amount = os_poker_number_format(arg.amount);
        }
        event.data.text(arg.amount);
      }
    }
  }
}

function os_poker_init_events() {
  var nop = function(){};
  $.each(OsPoker.eventHandlers, function(event, handler) {
    if(typeof handler == 'function') {
      os_poker_bind_message(event, null, handler)
    }
    else {
      os_poker_bind_message(event, handler.selector ? $(handler.selector) : null, handler.fn || nop);
    }
  });
}