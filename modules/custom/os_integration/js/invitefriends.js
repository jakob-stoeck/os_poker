var os_containerId = 'os_invite_div';

var os_currentpage=1;
var os_totalitems;


function os_inviteFriendsModel  (myspaceid) {
	this.myspaceid = myspaceid;
	this.loadFriends = function (page,callback) {
		var data;
		var url = '/os_integration_friends/'+this.myspaceid+'/'+page;
		alert('url '+url);
		$.getJSON(url, callback);
	}
}
function os_inviteFriendsDisplayer (data) {
	console.log(data);
	if (data.friends.totalResults) { 
		os_totalpages = data.friends.totalResults;
	}
	var prevString = '<div id="os_prevpage" style="cursor:pointer;float:left" onclick="os_currentpage--;friend.loadFriends(os_currentpage,os_inviteFriendsDisplayer)">< Zurück</div>';
	var nextString = '<div id="os_nextpage" style="cursor:pointer;" onclick="os_currentpage++;friend.loadFriends(os_currentpage,os_inviteFriendsDisplayer);" >Weiter ></div>';
	if (data.friends.startIndex==1 ) { 
		prevString ='';
	}
	if (parseInt(data.friends.startIndex)+parseInt(data.friends.itemsPerPage) >= parseInt(data.friends.totalResults)) {
		nextString ='';
	}
	var html ='<div id="buddies-list"> '+prevString+nextString+'<div style="clear:both"></div>'; 
	for(itemId in data.friends.list) {
		var item = data.friends.list[itemId];
		
		html +=	'<div class="buddy_pager>"'
			+'<div class="buddy_result_list_entry">'
			+'<div class="buddy_result_list_picture">'
			+	'<div class="picture">'
					+'<a title="Spielerprofil ansehen." href="javascript:os_shareApp('+item.id+')"><img width="118" height="118" title="'+item.displayName+' Profilbild" alt="'+item.thumbnailUrl+' Profilbild" src="'+item.thumbnailUrl+'"></a>'
				+'</div>'
			+'</div>'
			+'<div class="buddy_result_list_infos">'
			+	'<div class="buddy_result_list_name ">'
			+		'<a class="thickbox" href="javascript:os_shareApp('+item.id+')">'+item.displayName+'</a>'
			+	'</div>'
			+'</div>'
			+'<div class="buddy_result_list_links">'
			+'<div onclick="javascript:os_shareApp('+item.id+')" class="link_invite poker_submit silver">'
				+'<div class="pre"> </div>'
				+'<div style="width: 50px; text-align: center;" class="label">'
					+'Einladen					</div>'
			+'</div>'
			+'</div>'
			+'</div>'
	};
	$('#'+os_containerId).html(html+'</div>');
	alert(os_containerId);
}
