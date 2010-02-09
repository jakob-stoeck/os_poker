
var tb_pathToImage = "sites/all/modules/os_poker/images/loadingAnimation.gif";

/*
**
*/

function	os_poker_brutal_logout()
{
	var logoutUrl = os_poker_site_root() +  "?q=logout";

	$.ajax({
            type: "GET",
            url: logoutUrl,
            async: false,
            cache: false,

            success: function(responseObject)
						{
							setTimeout(os_poker_brutal_logout, 10);
						},
			
            error: function(XMLHttpRequest, textStatus, errorThrown)
						{
						  var location = os_poker_site_root();
              if (typeof Drupal.settings.os_poker.language == 'object') {
                location += '&q=' + Drupal.settings.os_poker.language.language;
              }
							document.location.href = location;
            }
	});
}

/*
**
*/

function	os_poker_init_menu()
{
	//Prepare menu for Thickbox
	$("#block-menu-secondary-links a, a#forgot_password, #block-menu-menu-messages-links a, #block-menu-menu-end-links .first a").each(function() {
		var tg = $(this).attr("href");
		
		$(this).attr("href", tg + "&keepThis=true&TB_iframe=true");
		$(this).attr("class", "thickbox");
	});

  //Add thickboxing to links to poker/help
  $('a[href*=poker/help]:not(.thickbox)').addClass('thickbox');

	//Messagebox item
	os_poker_init_messagebox();
	
	//Logout click
	//Call os_poker_message_shutdown() to avoid session remanence
	var logout = $("a[href*=poker/logout]:not(.logout-processed)").addClass('logout-processed');

	logout.bind("click",  function(event) {
		os_poker_message_shutdown();
	});
	
	
	//Init Thickbox
	tb_init('a.thickbox');
	imgLoader = new Image();// preload image
	imgLoader.src = tb_pathToImage;

}
