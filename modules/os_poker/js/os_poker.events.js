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
var OsPoker = OsPoker || {};

OsPoker.eventHandlers = {
  os_poker_table_selected: function(event, arg) {
    if(!isNaN(parseInt(arg.table, 10))) {
      $('#table_users .inner-list').load(Drupal.settings.basePath+'?q='+Drupal.encodeURIComponent('poker/table/'+arg.table.toString()+'/players'), function(responseText, textStatus, XMLHttpRequest){
        if(textStatus === 'success' && $(this).find('.user').length > 0) {
          $('#table_users .header').show();
          $('#table_users #list-banner').hide();
          $('#table_users .list').removeClass('splash');
          Drupal.attachBehaviors(this);
          tb_init('#table_users a.thickbox');
        }
        else {
          $(this).html('');
          $('#table_users .header').hide();
          $('#table_users #list-banner').show();
          $('#table_users .list').addClass('splash');
        }
      });
    }
  },
  os_poker_jump: function(event, arg) {
  if (typeof(arg.url) != "undefined") {
      if (arg.lightbox === true) {
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
        if(!isNaN(parseInt(arg.amount, 10))) {
          arg.amount = os_poker_number_format(arg.amount);
        }
        event.data.text(arg.amount);
      }
    }
  },
  os_poker_gift_sent: function(event, arg) {
    if(typeof arg.text === 'string') {
      $('#today_gift').html(arg.text);
      //After 60secs. remove the today gift banner and show the invite buddy one.
      setTimeout(function(){
        $('#today_gift').hide();
        $('#today_gift_invite').show();
      }, 60000);
    }
  },
  os_poker_notify: function(event, arg) {
    if(typeof arg.text === 'string') {
      OsPoker.dialog($('<div>' + arg.text +'</div>').appendTo(document.body));
    }
  }
};

function os_poker_init_events() {
  var nop = function(){};
  $.each(OsPoker.eventHandlers, function(event, handler) {
    if(typeof handler == 'function') {
      os_poker_bind_message(event, null, handler);
    }
    else {
      os_poker_bind_message(event, handler.selector ? $(handler.selector) : null, handler.fn || nop);
    }
  });
}
