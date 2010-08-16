<?php
function os_integration_block($op='list',$delta=0,$edit =array()) {


$html = <<<ECHO
<style>
    body
    {
    	margin:0;
    	padding:0;
    	color:#000;
    	background-color:#fff;
    }
    img
    {
    	border:0;
    }
    #outer
    {
    	font-family:Verdana;
    	font-size:0.7em;
    	padding:5px;
    }
    #content
    {
    	display:none;
    }
    #spinner
    {
    	color:Red;
    	font-size:1.3em;
    }
    #friends_callout, app_friends_callout
    {
    	display:none;
    }
    .divider
    {
    	margin-bottom:5px;
    	border-bottom:solid 1px black;
    }
    .float
    {
    	float:left;
    }
    .clear
    {
    	clear:both;
    }
    .friend
    {
    	border:solid 1px black;
    	width:80px;
    	height:120px;_height:130px;
    	margin:5px;
    	cursor:pointer;
    	overflow:hidden;
    }
    .friend div
    {
    	vertical-align:top;
    }
    .friend img
    {
    	width:70px;
    	max-height:100px;
    	margin-top:5px;
    }
    .callout
    {
    	font-size:14px;
    	font-weight:bold;
    }
    .text
    {
    	font-size:14px;
    }
    .bottom
    {
    	padding-bottom:2px;
    }
    .bottom_10
    {
    	padding-bottom:10px;
    }
    .hide
    {
    	display:none;
    }
    .error
    {
    	font-size:11px;
    	color:Red;
    }
</style>
 
<div id="outer">
    <div id="spinner">
        <img src="http://x.myspacecdn.com/modules/common/static/img/loadercircles.gif" />
        Loading...
    </div>
    <div id="content">
 
        <div id="perms" class="divider callout bottom">No access to photos? Try these: <button onclick="ProfileDeluxe.reqPublic();">Public Photos</button> <button onclick="ProfileDeluxe.reqPrivate();">Private Photos</button></div>
        <div id="bulletin" class="divider callout bottom">Spread the love and send a <button onclick="ProfileDeluxe.sendBulletin();">Bulletin!</button></div>
        <div id="activity" class="callout bottom">Let your friends know what you're up to by sending an <button onclick="ProfileDeluxe.showActivity();">Activity!</button></div>
        <div id="activity_block" class="hide">
            <p class="text">Great! You need to fill in the blanks for "friend" and "action":</p>
 
            <p class="text">
                <span id="activity_name_1">Bob</span> says hi to (friend)!<br />
                On <span id="activity_date">Dec 29th</span>, <span id="activity_name_2">Bob</span> was (action) 
                <a href="http://profile.myspace.com/index.cfm?fuseaction=user.viewProfile&friendID=422742687">Profile Deluxe</a>
            </p>
 
 
            <div class="text float bottom_10">First pick a friend: </div>
            <select id="activity_friend"></select>
            <div class="clear"></div>
 
            <div class="text float bottom_10">Then pick an action: </div>
            <input id="activity_action" type="text" size="40" maxlength="40" value="looking at pictures" />
            <div class="clear"></div>
 
            <div class="text">
                ... and don't forget to add some pictures from below! (up to 3 allowed)<br />
 
                When you're done, click <button onclick="sendActivity();">Send!</button>
                <span id="activity_message" class="error"></span>
            </div>
            <div id="photos"></div>
            <div class="clear"></div>
        </div>
        <div id="activity_divider" class="divider"></div>
 
        <div id="friends_callout" class="callout">
            <button onclick="ProfileDeluxe.multipleRSAWrapper();">Invite</button> some friends!
        </div>
        <div id="friends" class="divider"></div>
 
        <div id="app_friends_callout" class="callout">
            Or, send some <button onclick="ProfileDeluxe.multipleNotificationWrapper();">Notifications!</button> 
            <span id="notificationMessage"></span>
        </div>
        <div id="app_friends" class="divider"></div>
    </div>
 
</div>
 
<script type="text/javascript">
    var ProfileDeluxe = {};
    ProfileDeluxe.Friends = {};
    ProfileDeluxe.Photos = {};
 
    // the entry point for the app, fired from the onload handler.
    ProfileDeluxe.init = function(){
 
		// check if the app is added
		ProfileDeluxe.is_added = ProfileDeluxe.isAdded();
 
        // create the opensocial.DataRequest object
        var request = opensocial.newDataRequest();
 
        if(ProfileDeluxe.is_added){
			// show the relevant divs
			document.getElementById("perms").style.display = "block";
			document.getElementById("bulletin").style.display = "block";
			document.getElementById("activity").style.display = "block";
			document.getElementById("friends_callout").style.display = "block";
			document.getElementById("app_friends_callout").style.display = "block";
			document.getElementById("friends").style.display = "block";
 
            // create the parameters for the idspec to send into the fetch app data request
            params = {};
            params[opensocial.IdSpec.Field.USER_ID] = opensocial.IdSpec.PersonId.VIEWER;
            params[opensocial.IdSpec.Field.NETWORK_DISTANCE] = 1;
 
            // create the idspec
            var idspec = opensocial.newIdSpec(params);
 
            // add the fetch friend request to the queue
            request.add(request.newFetchPeopleRequest(idspec), "friends");
 
            // filter by users with the app
            params[opensocial.DataRequest.PeopleRequestFields.FILTER] = opensocial.DataRequest.FilterType.HAS_APP;
 
            // add the fetch friends with the app request to the queue
            request.add(request.newFetchPeopleRequest(idspec, params), "app_friends");
 
            // create the ID
            var id = opensocial.IdSpec.PersonId.VIEWER;
 
            // add the photos request to the queue
            request.add(MyOpenSpace.DataRequest.newFetchPhotosRequest(id), "photos");
        }
        else{
            // if the app isn't added, just get the basic viewer
            requestBasicViewer();
            return;
        }
 
        // start executing the request, call back into got_init when done
        request.send(ProfileDeluxe.got_init);
    }
 
    ProfileDeluxe.Friends.friends = {};
    ProfileDeluxe.Friends.app_friends = {};
 
    // the callback function for the initial data requests, 'response' is the opensocial.DataResponse object.
    ProfileDeluxe.got_init = function(response){
        // create flags to use so we don't try to parse a response that had an error.
        var parse_friends = true;
        var parse_app_friends = true;
        var parse_viewer = true;
        var parse_photos = true;
 		alert('init');
        // first check if an error occurred.
        if(!response){
            parse_friends = false;
            parse_app_friends = false;
            parse_viewer = false;
            parse_photos = false;
            alert('no responese');
        }
        else if(response.hadError()){
            var friends = response.get("friends");
            var app_friends = response.get("app_friends");
            var viewer = response.get("viewer");
            var photos = response.get("photos");
 			alert('has error');
            // create a locally scoped function to handle checking for errors
            var checkForError = function(ri){
                if(!ri){
                    return false;
                }
                else if(ri.hadError()){
                    if(ri.getErrorCode() === opensocial.ResponseItem.Error.INTERNAL_ERROR){
                        // retry the request?
                    }
                    return false;
                }
                return true;
            }
 
            parse_friends = checkForError(friends);
            parse_app_friends = checkForError(app_friends);
            parse_viewer = checkForError(viewer);
            parse_photos = checkForError(photos);
        }
        else{
            if(!response.get("friends")) parse_friends = false;
            if(!response.get("app_friends")) parse_app_friends = false;
            if(!response.get("viewer")) parse_viewer = false;
            if(!response.get("photos")) parse_photos = false;
        }
 
        if(parse_friends){
            ProfileDeluxe.Friends.friends.array = response.get("friends").getData().asArray();
            ProfileDeluxe.Friends.drawFriends(ProfileDeluxe.Friends.friends.array, "friends");
        }
 
        if(parse_app_friends){
            ProfileDeluxe.Friends.app_friends.array = response.get("app_friends").getData().asArray();
            ProfileDeluxe.Friends.drawFriends(ProfileDeluxe.Friends.app_friends.array, "app_friends");
        }
 
        var viewer;        
        if(parse_viewer){
            viewer = response.get("viewer").getData();
            document.getElementById("friends").style.display = "none";
        }
 
        if(parse_photos){
            photos = response.get("photos").getData().asArray();
 
            ProfileDeluxe.Photos.drawPhotos(photos);
        }
 
        ProfileDeluxe.setPermsDiv(viewer);
 
        // hide the spinner and show the content now that the DOM has been populated.
        document.getElementById("spinner").style.display = "none";
        document.getElementById("content").style.display = "block";
 
        // make the iframe fit the new content.
        gadgets.window.adjustHeight();
    }
 
    // the global app is added flag
    ProfileDeluxe.is_added = null;
 
    // return true if the app is added
    // false otherwise
    ProfileDeluxe.isAdded = function(){
        // the first time around the flag is null, so use the
        // parameters to determine install state
        if(null === ProfileDeluxe.is_added){
            return ("1" === gadgets.views.getParams().installState + "");
        }
 
        // otherwise the flag was set directly, just return that
        else{
            return ProfileDeluxe.is_added;
        }
    }
 
    ProfileDeluxe.requestBasicViewer = function(){
        // create the opensocial.DataRequest object.
        var request = opensocial.newDataRequest();
 
        // specify the viewer
        var id = opensocial.IdSpec.PersonId.VIEWER;
 
        // add the basic viewer request to the queue
        request.add(request.newFetchPersonRequest(id), "viewer");
 
        // start executing the request, call back into got_init when done.
        request.send(ProfileDeluxe.got_init);
    }
 
	// generate the markup for how to show permissions
	// based off of the user having added the app
	// and what permissions they already have set
	ProfileDeluxe.setPermsDiv = function(viewer){
		var inner = "";
		var perms = document.getElementById("perms");
 
		// first check if the app is added
		if(!ProfileDeluxe.is_added){
		    if(!viewer.hadError()){
			    var viewer_name = (viewer) ? " " + viewer.getDisplayName() : "";
 
			    // it's not added so no permissions have yet been set
			    // show both permissions, if these are clicked the add
			    // app modal will show first
			    inner = 'Welcome to Profile Deluxe';
			    inner += viewer_name;
			    inner += '!<br />Check out the app by allowing access ';
			    inner += 'to some of your photos: ';
			    inner += '<button onclick="reqPublic();">';
			    inner += 'Public Photos</button> ';
			    inner += '<button onclick="reqPrivate();">';
			    inner += 'Private Photos</button>';
 
            }
			else{
			    inner = "Oops, it looks like Open Canvas isn't switched on right now,<br />";
			    inner += "or some other error occurred, check back later or install the app."
			}
 
		    // hide the bulletin and activity buttons as
		    // requestSendMessage and requestCreateActivity won't
		    // work when the user hasn't added the app
		    document.getElementById("bulletin").style.display = "none";
		    document.getElementById("activity_divider").style.display = "none";
		    document.getElementById("activity").style.display = "none";
		    document.getElementById("friends").style.display = "none";
		}
		else{
 
			// permission object for public videos and photos
			var pub = MyOpenSpace.Permission.VIEWER_ACCESS_TO_PUBLIC_VIDEOS_PHOTOS;
 
			// permission object for private videos and photos
			var pri = MyOpenSpace.Permission.VIEWER_ACCESS_TO_PRIVATE_VIDEOS_PHOTOS;
 
			// check if both permissions are already granted
			if(opensocial.hasPermission(pub) &&
							opensocial.hasPermission(pri)){
				// if so, no need to display any permission buttons
				perms.style.display = "none";
			}
			else{
				inner += "No access to photos? You can try: ";
 
				// add the public photo permission button if necessary
				if(!opensocial.hasPermission(pub)){
					inner += "<button onclick='reqPublic();'>";
					inner += "Public Photos</button> ";
				}
 
				// add the private photo permission button if necessary
				if(!opensocial.hasPermission(pri)){
					inner += "<button onclick='reqPrivate();'>";
					inner += "Private Photos</button>";
				}
			}
		}
 
		perms.innerHTML = inner;
	}
 
    // global variable used to hold the ID of the clicked users
    ProfileDeluxe.Friends.friend_clicked = [];
    ProfileDeluxe.Friends.app_friend_clicked = [];
 
    // wrapper for requestShareApp
    ProfileDeluxe.Friends.friendClicked = function(id, type){
        var friends_array;
 
        switch (type){
            case "friends":
                friends_array = ProfileDeluxe.Friends.friend_clicked;
                break;
            case "app_friends":
                friends_array = ProfileDeluxe.Friends.app_friend_clicked;
                break;
        }
 
        // look for duplicates
        for(var i = 0; i < friends_array.length; i++){
            if(friends_array[i] === id){
                // duplicate found, remove it from the list and reset the background
                document.getElementById(type + "_" + id).style.backgroundColor = "white";
                friends_array.splice(i, 1);
                return;
            }
        }
 
        // not a duplicate, add the id to the list
        document.getElementById(type + "_" + id).style.backgroundColor = "yellow";
        friends_array.push(id);
    }
 
    // wrap opensocial.requestShareApp
    ProfileDeluxe.multipleRSAWrapper = function(){
        // create the rSA message
        var body = "Hey [recipient]! [sender] wants you to ";
        body += "add [app]. It's way awesome!";
        var title = "A fun way to view your profile!";
 
        var params = {};
        params[opensocial.Message.Field.TITLE] = title;
 
        // create an opensocial.Message object
        var reason = opensocial.newMessage(body, params);
 
        // initiate requestShareApp
        opensocial.requestShareApp(ProfileDeluxe.Friends.friend_clicked, reason, ProfileDeluxe.rsaCallback);
    }
 
    ProfileDeluxe.notiPermCB = function(){
        // re-check perms
        // or does the response contain the new perms?
        if(opensocial.hasPermission(MyOpenSpace.Permission.VIEWER_SEND_NOTIFICATIONS)){
            ProfileDeluxe.multipleNotificationWrapper();
        }
    }
 
    // wrap requestCreateNotification
    ProfileDeluxe.multipleNotificationWrapper = function(){
        if(!opensocial.hasPermission(MyOpenSpace.Permission.VIEWER_SEND_NOTIFICATIONS)){
            opensocial.requestPermission([MyOpenSpace.Permission.VIEWER_SEND_NOTIFICATIONS], "In order to update you on Profile Deluxe.", notiPermCB);
            return;
        }
 
        var body = "Hi ${recipient}, here's a notification from <a href='${canvasUrl}'>Profile Deluxe</a>";
        var url1 = MyOpenSpace.NotificationButton.UrlTypes.CANVAS;
        var url2 = MyOpenSpace.NotificationButton.UrlTypes.APP_PROFILE;
        var params1 = { "k1" : "v1", "k2" : "v2" };
        var text1 = "GOTO Canvas";
        var text2 = "GOTO Profile";
 
        var button1 = MyOpenSpace.newNotificationButton(url1, text1, params1);
        var button2 = MyOpenSpace.newNotificationButton(url2, text2);
 
        var param = {};
 
        param[MyOpenSpace.Notification.Field.BODY] = body;
        param[MyOpenSpace.Notification.Field.BUTTONS] = [button1, button2];
 
        var mediaItemArray = [];
        mediaItemArray.push(opensocial.newMediaItem("", MyOpenSpace.MediaItemHelper.PROFILE_PICTURE));
        param[MyOpenSpace.Notification.Field.MEDIA_ITEMS] = mediaItemArray;
 
        var notification = MyOpenSpace.newNotification(param);
 
        MyOpenSpace.requestCreateNotification(ProfileDeluxe.Friends.app_friend_clicked, notification, ProfileDeluxe.rcnCallback);
    }
 
    ProfileDeluxe.rcnCallback = function(response){
        if(response.hadError()){
            document.getElementById("notificationMessage").innerHTML = "Boo, there was an error!";
        }
        else{
            document.getElementById("notificationMessage").innerHTML = "Yay, notification sent!";
        }
 
        for(var i = 0; i < ProfileDeluxe.Friends.app_friend_clicked.length; i++){
            document.getElementById("app_friends_" + ProfileDeluxe.Friends.app_friend_clicked[i]).style.backgroundColor = "white";
        }
 
        ProfileDeluxe.Friends.app_friend_clicked = [];
    }
 
    // global variable used to hold the IDs of the clicked photos
    ProfileDeluxe.Photos.photos_clicked = [];
 
    ProfileDeluxe.Photos.photoClicked = function(id){
 
        // locate the particular clicked photo
        var photo = document.getElementById("photo_" + id);
 
        // figure out if the photo is currently clicked
        // or not by iterating through the photos_clicked
        // array, save the index if found
        var photo_exists = -1;
        for(var i = 0; i < photos_clicked.length; i++){
            if(photos_clicked[i] === id){
                photo_exists = i;
                break;
            }
        }
 
        // the photo was already clicked
        if(photo_exists >= 0){
 
            // remove the photo from the list
            photos_clicked.splice(i, 1);
 
            // set the background to white
            photo.style.backgroundColor = "white";
        }
 
        // the photo wasn't clicked
        else{
 
            // a maximum of 3 photos are allowed
            // so take no action if 3 are already
            // clicked
            if(photos_clicked.length > 2) return;
 
            // add the photo to the array
            photos_clicked.push(id);
 
            // set the photo's background to yellow
            photo.style.backgroundColor = "yellow";
        }
    }
 
    ProfileDeluxe.reqPublic = function(){
        var permissions = [MyOpenSpace.Permission.VIEWER_ACCESS_TO_PUBLIC_VIDEOS_PHOTOS];
        var reason = "This is to allow the app to display your photos that are set to public on your home page.";
        opensocial.requestPermission(permissions, reason, ProfileDeluxe.reqCallback);
    }
 
    ProfileDeluxe.reqPrivate = function(){
        var permissions = [MyOpenSpace.Permission.VIEWER_ACCESS_TO_PRIVATE_VIDEOS_PHOTOS];
        var reason = "This is to allow the app to display your photos that are set to private on your home page.";
        opensocial.requestPermission(permissions, reason, ProfileDeluxe.reqCallback);
    }
 
    // callback function for requestPermission
    ProfileDeluxe.reqCallback = function(response){
 
        // if the app was previously not added, but is
        // now added, reload the app
        if(!ProfileDeluxe.is_added && "1" === gadgets.views.getParams().installState + ""){
 
            // set the global flag
            ProfileDeluxe.is_added = true;
 
            // restart the app
            init();
        }
    }
 
    // if id starts with 'myspace.com:' it
    // will be stripped out and the resulting
    // value returned, otherwise return
    // the id as-is
    ProfileDeluxe.stripMySpaceDotCom = function(id){
        if(0 === id.indexOf("myspace.com:")){
            return id.substr("myspace.com:".length);
        }
        else{
            return id;
        }
    }
 
    ProfileDeluxe.showActivity = function(){
        document.getElementById("activity").style.display = "none";
        document.getElementById("activity_block").style.display = "block";
        document.getElementById("activity_divider").style.display = "block";
 
        gadgets.window.adjustHeight();
    }
 
    // wrapper for requestCreateActivity
    ProfileDeluxe.sendActivity = function(){
 
        // the date variable is of type Literal
        var date = (new Date()).toLocaleDateString();
 
        // the action variable is also a Literal
        var action = document.getElementById("activity_action").value;
 
        // the friend varaible is of type Person, so 
        // parse out the ID of the selected friend
        var friend = document.getElementById("activity_friend");
        friend = friend.options[friend.selectedIndex].value;
 
        // note that there is currently a bug here, the template
        // does not accept an ID in the format "myspace.com:12345"
        // so strip out the "myspace.com" part of the ID to leave
        // just "12345"
        friend = ProfileDeluxe.stripMySpaceDotCom(friend);
 
        // create the parameters for the activity
        var params = {};
 
        // select the particular template here
        params[opensocial.Activity.Field.TITLE_ID] = "date_message";
 
        // use the variables from above to generate the values
        // for the template variables
        params[opensocial.Activity.Field.TEMPLATE_PARAMS] = 
            { "date" : date, "action" : action, "friend" : friend };
 
        // iterate through the clicked photos
        if(photos_clicked.length > 0){
 
            // if some photos were clicked create an array to
            // store opensocial.MediaItem objects for each
            // photo
            var mediaItemArray = [];
 
            var uri, mi, photo_div, div_id;
            for(var i = 0; i < photos_clicked.length; i++){
                div_id = "photo_" + photos_clicked[i];
                photo_div = document.getElementById(div_id);
 
                // media items URIs are required to be in the format
                // of valid MySpace API URIs, these are supplied by
                // the API, here they are saved to a custom
                // "uri" attribute
                uri = photo_div.attributes.uri.nodeValue;
 
                // create the opensocial.MediaItem object
                mi = opensocial.newMediaItem("image/jpeg", uri);
 
                // add it to the array
                mediaItemArray.push(mi);
            }
 
            // once done, add the array to the activity parameters
            params[opensocial.Activity.Field.MEDIA_ITEMS] = mediaItemArray;
        }
 
        // create the opensocial.Activity object
        var activity = opensocial.newActivity(params);
 
        // request the event be raised, passing in the activity,
        // a priority of HIGH, and specify a callback
        opensocial.requestCreateActivity(activity, 
            opensocial.CreateActivityPriority.HIGH, ProfileDeluxe.activityCallback);
    }
 
    // handles the callback from requestCreateActivity
    ProfileDeluxe.activityCallback = function(response){
 
        // first check if the activity had an error
        if(!response || response.hadError() || -1 === response.getData()){
            document.getElementById("activity_message").innerHTML = 
                "There was an error, please refresh the page and try again!";
        }
        else{
            if(1 === response.getData()){
                // the user clicked "publish"
            }
            else if(0 === response.getData()){
                // the user clicked "deny"
            }
 
            // otherwise, take some action, in this case
            // reset the photo state
            var photo;
            for(var i = 0; i < ProfileDeluxe.Photos.photos_clicked.length; i++){
                photo = document.getElementById("photo_" + ProfileDeluxe.Photos.photos_clicked[i]);
                photo.style.backgroundColor = "white";
            }
 
            ProfileDeluxe.Photos.photos_clicked = [];
        }
    }
 
    // wrapper for requestSendMessage
    ProfileDeluxe.sendBulletin = function(){
        // link to the app's canvas
        var app_canvas = "http://profile.myspace.com/Modules/Applications/Pages/Canvas.aspx?appId=118991";
 
        // link to the app's icon
        var img_src = "http://c3.ac-images.myspacecdn.com/images02/21/l_e2b0c1f6a7b249a2a50d9a57444c5f12.jpg";
 
        // create the message
        var body = "Try it out! A fun new way to view your profile! Click ";
        body += "<a href='" + app_canvas + "'>here</a> to try it out!<br />";
        body += "<center><img src='" + img_src + "' /></center>"
        var title = "A fun way to view your profile!";
 
        var params = {};
        params[opensocial.Message.Field.TITLE] = title;
 
        // specify a message type of bulletin
        params[opensocial.Message.Field.TYPE] = opensocial.Message.Type.NOTIFICATION;
 
        var message = opensocial.newMessage(body, params);
 
        // initiate requestSendMessage
        opensocial.requestSendMessage(opensocial.IdSpec.PersonId.VIEWER, message, ProfileDeluxe.bulletinCallback);
    }
 
    ProfileDeluxe.bulletinCallback = function(response){
        if(response && !response.hadError()){
            if(1 === response.getData()){
                document.getElementById("bulletin").innerHTML = "Thanks!";
            }
        }
    }
 
    // callback for requestShareApp
    ProfileDeluxe.rsaCallback = function(response){
        if(response && !response.hadError()){
 
            for(var i = 0; i < ProfileDeluxe.Friends.friend_clicked.length; i++){
 
                // use the global friend_clicked variable to
                // grab the correct div from the DOM
                var friend = document.getElementById("friends_" + ProfileDeluxe.Friends.friend_clicked[i]);
 
                if(friend){
 
                    // if "Send" was clicked the response data
                    // will be 1, otherwise a 0
                    if(1 === response.getData()){
 
                        // remove that friend from the list
                        if(friend.parentNode){
                            friend.parentNode.removeChild(friend);
                        }
                    }
                    else{
                        friend.style.backgroundColor = "red";
                    }
                }
            }
        }
 
        ProfileDeluxe.Friends.friend_clicked = [];
    }
 
    ProfileDeluxe.rntWrapper = function(view_name){
        var supported_views = gadgets.views.getSupportedViews();
 
        var view = null;
        for(var v in supported_views){
            if(view_name === supported_views[v].getName()){
                view = supported_views[v];
                break;
            }
        }
 
        if(null !== view){
            gadgets.views.requestNavigateTo(view);
        }
    }
 
    // parses the friends response and outputs it to the page
    ProfileDeluxe.Friends.drawFriends = function(friends, friend_type){
        var friends_div, activity_friend = null;
        switch(friend_type){
            case "app_friends":
                document.getElementById("app_friends_callout").style.display = "block";
                friends_div = document.getElementById("app_friends");
                break;
            case "friends":
                document.getElementById("friends_callout").style.display = "block";
                friends_div = document.getElementById("friends");
                activity_friend = document.getElementById("activity_friend");
                break;
        }
 
        var clear_div = '<div class="clear"></div>';
        var friend_format = '<center><img src="{0}" /><div>{1}</div></center>';
 
        var container = document.createElement("div");
 
        var id, image, name;
        for(var i = 0; i < friends.length; i++){
            id = friends[i].getId();
            image = friends[i].getField(opensocial.Person.Field.THUMBNAIL_URL);
            name = friends[i].getDisplayName();
 
            if(!id || !image || !name) continue;
 
            var div = document.createElement("div");
            div.id = friend_type + "_" + id;
            div.className = "friend float";
            div.innerHTML = friend_format.replace("{0}", image).replace("{1}", name);
 
            if(div.atachEvent){
                div.attachEvent("onclick", "ProfileDeluxe.Friends.friendClicked('" + id + "', '" + friend_type + "')");
            }
            else{
                div.setAttribute("onclick", "ProfileDeluxe.Friends.friendClicked('" + id + "', '" + friend_type + "')");
            }
 
            container.appendChild(div);
 
            if(null !== activity_friend){
                var option = document.createElement("option");
                option.value = id;
                option.text = name;
                activity_friend.options.add(option);
            }
        }
 
        friends_div.appendChild(container);
 
        if(friends.length > 0){
            friends_div.innerHTML += clear_div;
        }
    }
 
    // parses the photos response and outputs it to the page
    ProfileDeluxe.Photos.drawPhotos = function(photos){
 
        // build up the required markup
        var clear_div = '<div class="clear"></div>';
        var photos_format = '<div id="photo_{0}" onclick="photoClicked';
        photos_format += '(\'{1}\');" class="friend float" uri="{2}">';
        photos_format += '<center><img src="{3}" />';
        photos_format += '<div>{4}</div></center></div>';
        var photos_div = document.getElementById("photos");
 
        // iterate through each photo
        var id, src, uri, caption;
        for(var i = 0; i < photos.length; i++){
            id = photos[i].getField(MyOpenSpace.Photo.Field.PHOTO_ID);
            src = photos[i].getField(MyOpenSpace.Photo.Field.IMAGE_URI);
            uri = photos[i].getField(MyOpenSpace.Photo.Field.PHOTO_URI);
            caption = photos[i].getField(MyOpenSpace.Photo.Field.CAPTION);
 
            if(!id || !uri) continue;
 
            if(!caption) caption = "";
 
            var this_photo = photos_format;
            this_photo = this_photo.replace("{0}", id);
            this_photo = this_photo.replace("{1}", id);
            this_photo = this_photo.replace("{2}", uri);
            this_photo = this_photo.replace("{3}", src);
            this_photo = this_photo.replace("{4}", caption);
 
            photos_div.innerHTML += this_photo;
        }
 
        // add the clearing div if necessary
        if(photos.length > 0){
            photos_div.innerHTML += clear_div;
        }
    }
 
    // add the app's entry point to the onload handler, this will fire when the body has finished loading.
    gadgets.util.registerOnLoadHandler(ProfileDeluxe.init);
</script>
ECHO;
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