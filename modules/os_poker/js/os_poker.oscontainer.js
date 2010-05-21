/**
 * Register a gadget refresh every 50min to avoid expired token.
 *
 * TODO: use Shindig auth-refresh feature: Do an AJAX call to get a fresh token,
 * then send it to the gadget using someting like
 *   gadget.rpc('remote_iframe_os_poker', 'update_security_token', null, token);
 */
jQuery(function($) {

  var delay = 1000 * 60 * 50;

  function refreshGadget(wrapper) {
    $(wrapper).load(Drupal.settings.basePath +'?q=poker/gadget&ajax=true iframe#remote_iframe_os_poker');
  }
  
  var poker_iframe = $('iframe#remote_iframe_os_poker');
  if(poker_iframe.length) {
    window.setInterval(refreshGadget, delay, poker_iframe.parents('.iframe_div')[0]);
  }
});
