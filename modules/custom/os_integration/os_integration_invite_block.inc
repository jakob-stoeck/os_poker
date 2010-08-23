<?php
function os_integration_block($op='list',$delta=0,$edit =array()) {
global $user;
	$html = '
				<script src="/'. drupal_get_path('module','os_integration').'/js/invite_ui.js"></script>
	<script>
			$(document).ready(function(){
			
				friend = new os_inviteFriendsModel('.$user->_osu.');
				friend.loadFriends(1,os_displayController);
				
			});
			function os_displayController(data) {
				var os_containerId = \'os_invite_div\';
				os_inviteFriendsDisplayer(data,document.getElementById(os_containerId));
			}
			
			/*
			gadgets.rpc.setRelayUrl(\'..\', \'http://profile.myspace.com/Modules/Applications/Pages/rpc_relay.aspx\'); 
// set the correct panel id. The name will be apppanel_XXXXXX_canvas where
// XXXXXX is the id of the application
    MyOpenSpace.MySpaceContainer.get().registerParam("panelId", "apppanel_'.$_SESSION['myspace_appid'].'_canvas");
//Set the location of the ifpc_relay_external001.html within your server
    MyOpenSpace.MySpaceContainer.get().registerParam("localRelay", "http://'.$_SERVER['HTTP_HOST'].'/'.drupal_get_path('module','os_integration').'/relay.html");
*/
    </script>

<div id="os_invite_div"></div>

';	
	switch ($op) {
		case 'list':
			$blocks[0]['info']=t('Invite Myspace friends');
			$blocks[0]['cache']=BLOCK_NO_CACHE;
			return $blocks;
		case 'view':
			$block['subject']=t('Invite Myspace friends');
			$block['content']=$html;
			return $block;
	}

}
?>