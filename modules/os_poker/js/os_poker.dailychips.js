/*
 * this script is included in the messagebox iframe, and resets the unread message count in the navbar.
 */ 

Drupal.behaviors.dailygiftBehavior = function() {
// Trigger invite friends thickbox instead of dailychips confirmation
setTimeout(function() {
   tb_show('Daily Gifts', 'http://' + window.location.hostname +'/drupal6/?q=poker/buddies/invite&height=442&width=603&TB_iframe=true');
}, 1000);
}


