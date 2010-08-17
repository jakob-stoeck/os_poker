<?php
function os_integration_block($op='list',$delta=0,$edit =array()) {

	$html = '<html>
			<head>
			<script type="text/javascript" src="http://js.myspacecdn.com/OpenSocial/RPC/RpcContainer.003.js"></script> 
			<script src="/'. drupal_get_path('module','os_integration').'/js/IFPC_externalIframe011.js"></script>
			<script>
			gadgets.rpc.setRelayUrl(\'..\', \'http://profile.myspace.com/Modules/Applications/Pages/rpc_relay.aspx\'); 
// set the correct panel id. The name will be apppanel_XXXXXX_canvas where
// XXXXXX is the id of the application
    MyOpenSpace.MySpaceContainer.get().registerParam("panelId", "apppanel_'.$_SESSION['myspace_appid'].'_canvas");
//Set the location of the ifpc_relay_external001.html within your server
    MyOpenSpace.MySpaceContainer.get().registerParam("localRelay", "http://'.$_SERVER['HTTP_HOST'].'/'.drupal_get_path('module','os_integration').'/relay.html");
			
    function shareApp(){
    
			    var reason = "toller grund " ;;
				var recipients = [];
				
				var shareAppIds = 1333;
				for (var i = 0; i < shareAppIds.options.length; i++) {
					var val = shareAppIds.options[i].value;
					recipients.push(val);
				}
				
				
				if (recipients.length < 1) return;
				var callback = function(response){
					var resultsDiv = document.getElementById(\'shareAppResult\');
					if (resultsDiv.style.color == "Blue"){
						resultsDiv.style.color = "Red";										
					}
					else{
						resultsDiv.style.color = "Blue";					
					}
					if (response.errorMessage){
						resultsDiv.innerHTML = "Error:" + response.errorMessage;
						return;	
					}
					var code = response.ResultCode;
					var summary;
					switch (code){
						case MyOpenSpace.PostTo.Result.ERROR:
							summary = "Error";
						break;
						case MyOpenSpace.PostTo.Result.CANCELLED:
							summary = "Cancelled";
						break;
						case MyOpenSpace.PostTo.Result.SUCCESS:
							summary = "Success";
						break;					
					}
					var getIds = function(values){
						var vals = '';
						for(var item in values){
							vals = vals + values[item] + \', \';
						}
						return vals;
					}
					if (typeof response.ResponseValues !== \'undefined\'){
						summary += "<br />falures: " + getIds(response.ResponseValues.failure);
						summary += "<br />success: " + getIds(response.ResponseValues.success);
					}
					resultsDiv.innerHTML = summary;
				}
				var params = {};
				var message = container.newMessage(reason, params);

				var inviteParams = {};
                var parametersList = document.getElementById('shareAppParams');
                for (var i = 0; i < parametersList.options.length; i++) {
                    var val = parametersList.options[i].value.split('=');
                    var key = val[0];
                    var value = val[1];
                    inviteParams[key] = value;
                }
                var navParams = {};
                navParams[MyOpenSpace.NavigationParameters.Field.DESTINATION_TYPE] = MyOpenSpace.NavigationParameters.DestinationType[document.getElementById('shareAppDestinationType').value];
                navParams[MyOpenSpace.NavigationParameters.Field.PARAMETERS] = inviteParams;
                var navigationParams = container.newNavigationParameters(navParams);
                container.requestShareApp(recipients, message, callback, [navigationParams]);
			}
    

    </script>
			
			</head>
<body onload="shareApp();">HELLLLLLLLLLOOOOOOOOOO<div id="shareAppResult">DIV</div></body>
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