<?php
define ('OPENSOCIAL','opensocial');


require_once drupal_get_path('module','os_integration').'/osapi/osapi.php';
function os_integration_get_osapi() {
	$authprovider = new osapiOAuth2Legged(variable_get('os_integration_consumer_key',array()),variable_get('os_integration_consumer_secret',array()),$myspace_profile_id);
	$myspaceprovider = new osapiMySpaceProvider();
	$osapi = new osapi($myspaceprovider,$authprovider);
	return $osapi;
}
function os_integration_map_friends($account) {
	$osapi = os_integration_get_osapi();
	$myspace_profile_id =  $account->_osu;
	$batch = $osapi->newBatch();

	$profile_fields = array();
 $self_request_params = array(
 'userId' => $myspace_profile_id, 
 'groupId' => '@friends', 
 'fields' => $profile_fields 
 );
 $batch->add($osapi->people->get($self_request_params), 'friends');
 $response = $batch->execute();
 $persons = $response['friends']->getList() ;
 $oids = array();
 foreach ($persons as $key=>$person) {
 	$idary	= explode('.',$person->id);
 	$oids[] = $idary[3];
 }
 if (count($oids)==0) { 
 	return ;
 }
    $query = "SELECT uid FROM {authmap}
        left join {user_relationships} on {user_relationships}.requestee_id = {authmap}.uid
	and {user_relationships}.requester_id=%s
        WHERE authname IN (%s) AND module='%s'  and {user_relationships}.requestee_id is null"  ; // better? way using authmap.
    $args[] = $account->uid;
    $args[] = implode(',', $oids);
    $args[] = 'os_user';

    $result = db_query($query, $args);
    $uids = array();
    while ($data = db_fetch_object($result)) {
      if ($data->uid) {
        $uids[] = $data->uid;
      }
    }
    if (count($uids)==0) {
        return ;
    }
    $rtype = user_relationships_type_load(array('name'=>'buddy'));

    for ($i=0;$i<count($uids);$i++) {
      $relationship = new stdClass();
      $relationship->requestee_id = $uids[$i];
      $relationship->requester_id=$account->uid;
      $relationship->rtid =$rtype->rtid;
      $relationship->approved = 1;
      user_relationships_save_relationship($relationship,'osapprove');
    }
 
 
 
}
function os_integration_get_profile_infos($myspace_profile_id) {
	$osapi = os_integration_get_osapi();
	$batch = $osapi->newBatch();
	$profile_fields = array(
 '@all'
 );
 $self_request_params = array(
 'userId' => $myspace_profile_id, 
 'groupId' => '@self', 
 'fields' => $profile_fields 
 );
 $batch->add($osapi->people->get($self_request_params), 'friends');
 $response = $batch->execute();

 return $response['self'];
}
function os_integration_create_profile($myspace_profile_id) {
	global $user;
	//$infos = os_integration_get_profile_infos($myspace_profile_id);

	$edit = array();
	$edit['roles'][DRUPAL_AUTHENTICATED_RID] = 'authenticated user';

	// Ensure unique username.  Append "_N" if necessary.
	if (isset($edit['name']) && $edit['name']) {
		$username = $edit['name'];
	}
	else {
		$username = "$myspace_profile_id@myspace.com";
		$edit['name'] = $username;
	}
	$i = 1;
	while (db_result(db_query("SELECT name FROM {users} WHERE name='%s'", $edit['name']))) {
		$i++;
		$edit['name'] = $username . '_' . $i;
	}
	$defaults = array(
      'pass' => user_password(),
      'init' => db_escape_string($edit['name']),
      'status' => 1,
      'authname_os_user' => $myspace_profile_id, // authmap entry
	);
	$defaults['mail'] = $myspace_profile_id.'@localhost.com';
	$edit = array_merge($defaults, $edit);
	$edit['_osu']=$myspace_profile_id;

	$user = user_save('', $edit);

	watchdog('opensocial', 'New user: %name %email.',
	array('%name' => $account->name, '%email' => '<'. $account->mail .'>'),
	WATCHDOG_NOTICE, l(t('edit'), 'user/'. $account->uid .'/edit'));

}

function os_integration_get_user_id($authname = NULL) {
	$result = db_query("SELECT uid, module FROM {authmap} WHERE authname = '%s'", $authname);
	$authmaps = array();
	$has_rows = FALSE;
	while ($authmap = db_fetch_object($result)) {
		$authmaps[$authmap->module] = $authmap->uid;
		$has_rows = TRUE;
	}
	return $has_rows ? $authmaps : 0;
}

function os_integration_login_opensocial_user($myspace_profile_id) {
	global $user;
	$ids = os_integration_get_user_id($myspace_profile_id);
	if (isset($ids['os_user'])) {
		
		$user = user_load($ids['os_user']);
		sess_regenerate();
		watchdog('opensocial', 'get user: '.$user->uid. ' '.$usser->name);
		os_integration_map_friends($user);
		return true;
	}
	return false;
}
?>