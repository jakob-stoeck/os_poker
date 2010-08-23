var os_currentpage=1;
var os_totalitems;

function os_shareApp(myspaceid){
	
    var container =  MyOpenSpace.MySpaceContainer.get();
			    var reason = "Join Playboy Poker" ;
	var recipients = [];
	
	recipients.push(myspaceid)
	
	if (recipients.length < 1) return;
	var callback = function(response){
	
		
		if (response.errorMessage){
			alert( "Error:" + response.errorMessage);
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
				vals = vals + values[item] + ', ';
			}
			return vals;
		}
		if (typeof response.ResponseValues !== 'undefined'){
			summary += "<br />falures: " + getIds(response.ResponseValues.failure);
			summary += "<br />success: " + getIds(response.ResponseValues.success);
		}
		
	}
	var params = {};
	var message = container.newMessage(reason, params);

	var inviteParams = {}; 
	var navParams = {}; 
	navParams[MyOpenSpace.NavigationParameters.Field.DESTINATION_TYPE] = MyOpenSpace.NavigationParameters.DestinationType.RECIPIENT_DESTINATION; 
	navParams[MyOpenSpace.NavigationParameters.Field.PARAMETERS] = inviteParams; 
	var navigationParams = container.newNavigationParameters(navParams); 
        
    var navigationParams = container.newNavigationParameters(navParams);
    try { 
    	container.requestShareApp(recipients, message, callback, [navigationParams]);
    }catch (e) {
    	//console.log(e);
    	alert(e); 
    }
}

