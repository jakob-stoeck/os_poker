
var tb_pathToImage = "sites/all/modules/os_poker/images/loadingAnimation.gif";

function	os_poker_init_menu()
{
	//Prepare menu for Thickbox
	$("#block-menu-secondary-links a, a#forgot_password, #block-menu-menu-messages-links a, #block-menu-menu-end-links .first a").each(function() {
		var tg = $(this).attr("href");
		
		$(this).attr("href", tg + "&keepThis=true&TB_iframe=true");
		$(this).attr("class", "thickbox");
	});

	//Messagebox item
	os_poker_init_messagebox();
	
	//Logout click
	//Call os_poker_message_shutdown() to avoid session remanence
	var logout = $("#block-menu-menu-end-links .last a");

	logout.attr("href", "javascript:void(0);");
	logout.bind("click",  function(event) {
		os_poker_message_shutdown();

		$.get(os_poker_site_root(), {q:"logout"}, function() {
			document.location.href = os_poker_site_root();
		});
	});
	
	
	//Init Thickbox
	tb_init('a.thickbox');
	imgLoader = new Image();// preload image
	imgLoader.src = tb_pathToImage;

}