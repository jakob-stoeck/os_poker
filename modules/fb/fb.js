
// This function called by facebook's javascript when it is loaded.
window.fbAsyncInit = function() {
  //debugger; // debug
  // @TODO - make these settings completely customizable.
  var settings = {xfbml: true};
  if (Drupal.settings.fb.apikey) {
    settings.apiKey = Drupal.settings.fb.apikey;
    settings.status = true;
    settings.cookie = true;
  }
  
  FB.init(settings);

  // Async function to complete init.
  FB.getLoginStatus(function(response) {
    var status = {'session' : response.session, 'response': response};
    jQuery.event.trigger('fb_init', status);  // Trigger event for third-party modules.

    FB_JS.sessionChange(response);

    // Use FB.Event to detect Connect login/logout.
    FB.Event.subscribe('auth.sessionChange', FB_JS.sessionChange);
    
    // Other events that may be of interest...
    //FB.Event.subscribe('auth.login', FB_JS.debugHandler);
    //FB.Event.subscribe('auth.logout', FB_JS.debugHandler);
    //FB.Event.subscribe('auth.statusChange', FB_JS.debugHandler);
    //FB.Event.subscribe('auth.sessionChange', FB_JS.debugHandler);

  });

  
};

FB_JS = function(){};

/**
 * Reload the current page, whether on canvas page or facebook connect.
 */
FB_JS.reload = function(destination) {
  if (typeof(destination) != 'undefined' && destination) {
    window.top.location = destination;
  }
  else if (typeof(Drupal.settings.fb.reload_url) != 'undefined') {
    window.top.location = Drupal.settings.fb.reload_url;
  }
  else {
    window.location.reload();
  }
};

// Facebook pseudo-event handlers.
FB_JS.sessionChange = function(response) {
  var status = {'changed': false, 'fbu': null, 'session': response.session, 'response' : response};
  
  if (response.session) {
    status.fbu = response.session.uid;
    if (Drupal.settings.fb.fbu != status.fbu) {
      // A user has logged in.
      status.changed = true;
    }
  }
  else if (Drupal.settings.fb.fbu) {
    // A user has logged out.
    status.changed = true;
    
    // Sometimes Facebook's invalid cookies are left around.  Let's try to clean up their crap.
    // @TODO - delete cookie only if it exists.
    FB_JS.deleteCookie('fbs_' + Drupal.settings.fb.apikey, '/', '');
  }
  
  if (status.changed) {
    // fbu has changed since server built the page.
    jQuery.event.trigger('fb_session_change', status);

    // Remember the fbu.
    Drupal.settings.fb.fbu = status.fbu;
  }
  
};

// Helper function for developers.
FB_JS.debugHandler = function(response) {
  debugger;
};

// JQuery pseudo-event handler.
FB_JS.sessionChangeHandler = function(context, status) {
  // Pass data to ajax event.
  var data = {
    'apikey': FB._apiKey,
    'event_type': 'session_change'
  };
  
  if (status.session) {
    data.fbu = status.session.uid;
    // Suppress facebook-controlled session.
    data.fb_session_handoff = true;
  }
  FB_JS.ajaxEvent(data.event_type, data);
  // No need to call window.location.reload().  It will be called from ajaxEvent, if needed.
};

// click handler
FB_JS.logoutHandler = function(event) {
  if (typeof(FB) != 'undefined') {
    FB.logout(function () {
      //debugger;
    });
  }
};

// Helper to pass events via AJAX.
// A list of javascript functions to be evaluated is returned.
FB_JS.ajaxEvent = function(event_type, data) {
  if (Drupal.settings.fb.ajax_event_url) {

    // Include session, in the format faceboook's PHP sdk expects it.
    // @TODO - pass this only if fbs_APIKEY cookie is not set.
    data.session = JSON.stringify(FB.getSession());

    jQuery.post(Drupal.settings.fb.ajax_event_url + '/' + event_type, data,
		function(js_array, textStatus, XMLHttpRequest) {
		  //debugger; // debug
		  for (var i = 0; i < js_array.length; i++) {
		    eval(js_array[i]);
		  }
		}, 'json');
  }
};

// Delete a cookie.
FB_JS.deleteCookie = function( name, path, domain ) {
  document.cookie = name + "=" +
  ( ( path ) ? ";path=" + path : "") +
  ( ( domain ) ? ";domain=" + domain : "" ) +
  ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
};

// Test the FB settings to see if we are still truly connected to facebook.
FB_JS.sessionSanityCheck = function() {
  if (!Drupal.settings.fb.checkSemaphore) {
    Drupal.settings.fb.checkSemaphore=true;
    FB.api('/me', function(response) {
      if (response.id != Drupal.settings.fb.fbu) {
	// We are no longer connected.
	var status = {'changed': true, 'fbu': null, 'check_failed': true};
	jQuery.event.trigger('fb_session_change', status);
      }
      Drupal.settings.fb.checkSemaphore=null;
    });
  }
};

Drupal.behaviors.fb = function(context) {
  // Respond to our jquery pseudo-events
  var events = jQuery(document).data('events');
  if (!events || !events.fb_session_change) {
    jQuery(document).bind('fb_session_change', FB_JS.sessionChangeHandler);
  }
  
  if (typeof(FB) == 'undefined') {
    // Include facebook's javascript.  @TODO - determine locale dynamically.
    jQuery.getScript(Drupal.settings.fb.js_sdk_url);
  }
  else {
    $(context).each(function() {
      var elem = $(this).get(0);
      //alert('fb_connect: ' + elem + $(elem).html()); // debug
      FB.XFBML.parse(elem);
    });

    FB_JS.sessionSanityCheck();
  }

  // Markup with class .fb_show should be visible if javascript is enabled.  .fb_hide should be hidden.
  jQuery('.fb_hide', context).hide();
  jQuery('.fb_show', context).show();

  // Logout of facebook when logging out of drupal
  jQuery("a[href^='" + Drupal.settings.basePath + "logout']", context).click(FB_JS.logoutHandler);
};

