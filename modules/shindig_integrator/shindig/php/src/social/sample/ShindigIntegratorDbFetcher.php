<?php
// $Id: ShindigIntegratorDbFetcher.php,v 1.2.2.5 2009/08/13 09:42:55 impetus Exp $
/**
 * @file
 * DB interaction layer for shindig services
 *
 * @see http://incubator.apache.org/shindig/
 * This module contains core shindig server
 */

global $TEST_ENABLED;
if (! $TEST_ENABLED ) {
global $base_url;
$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://'. $_SERVER['HTTP_HOST'];
if($base_path = Config::get('drupal_base_path')) {
  $base_url .= $base_path;
}
$dir = getcwd();
chdir(Config::get('include_path').'/..');
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
chdir($dir);

}

class ShindigIntegratorDbFetcher {
	private $db;
	private $url_prefix;
	
	// Singleton
	private static $fetcher;

	private function __construct()
	{
		global $url_array;
		$this->url_prefix = "http://" . $_SERVER['HTTP_HOST'] . $url_array['path']."/";
	}

	private function __clone()
	{
		// private, don't allow cloning of a singleton
	}

	static function get()
	{
		// This object is a singleton
		if (! isset(ShindigIntegratorDbFetcher::$fetcher)) {
			ShindigIntegratorDbFetcher::$fetcher = new ShindigIntegratorDbFetcher();
		}
		return ShindigIntegratorDbFetcher::$fetcher;
	}
	
	/**
	* To get Friends IDs for the given UserID
	*
	* @param
	*   $userId for who friends are being retrived
	* @return
	*   $ret ids of all the friends of the user
	*/	
	public function getFriendIds($user_id)
	{
		$ret = array();
		$res = db_query("SELECT requester_id, requestee_id FROM user_relationships WHERE requester_id = %d OR requestee_id = %d AND rtid=(select rtid FROM user_relationship_types WHERE name='friend') AND approved=1", $user_id, $user_id);
		while ($row = db_fetch_array($res)) {
			if($row['requester_id']==$user_id)
			{
				if(!in_array($row['requestee_id'],$ret))
    			    $ret[] = $row['requestee_id'] ;
			}
			else
			{
    			if(!in_array($row['requester_id'],$ret))
                    $ret[] = $row['requester_id'] ;
			}
		}
		return $ret;
	}    

	/**
	* Get User(s) 
	* Return array of Person Model Objects
	* 
	* @param
	*   $ids of the userIds
	* @param
	*   $profileDetails profile fields to fetched for the users
	* @param
	*   $options other query options to get Users
	* @return
	*   $ret array of person objects 
	*/
	public function getPeople($ids, $profileDetails, $options)
	{
		$ret = array();
		foreach($ids as $id)
		{
			$user =array();
			$res = db_query("SELECT * FROM {profile_values} INNER JOIN {profile_fields} ON {profile_values}.fid = {profile_fields}.fid WHERE uid =%d", $id);		
			if ($res) {
				while ($row = db_fetch_array($res)) 
				{
					$user[$row['name']] = $row['value'];
					$user['uid'] = $row['uid'];
				}			
			}	
			$user_id = $user['uid'];
			$name = new Name($user['profile_fname'] . ' ' . $user['profile_lname']);
			$name->setGivenName($user['profile_fname']);
			$name->setFamilyName($user['profile_lname']);
			$address = new Address("UNSTRUCTUREDADDRESS");
			$address->setLocality($user['profile_city']);
			$address->setCountry($user['profile_country']);
			$person = new Person($user['uid'], $name);
			$person->setAddresses(array($address));
			$person->setNickname($user['profile_nickname']);
			$person->setProfileUrl($this->url_prefix . 'user/' . $user['uid']);
			$person->setDisplayName($user['profile_fname'] . ' ' . $user['profile_lname']);
			$res = db_query("SELECT picture FROM {users} WHERE uid = %d",$id);	
			$row =  db_fetch_array($res);
			$person->setThumbnailUrl(! empty($row['picture']) ? $this->url_prefix.$row['picture'] : '');
			if ($user['profile_gender'] == 'Female') {
			    $person->setGender('FEMALE');
			} else {
			    $person->setGender('MALE');
			}
			$ret[$user_id] = $person;
			
		}
		//Chnage current dir to the Drupal directory, so drupal code is executed from
		//the expected path.
		$dir = getcwd();
		chdir(Config::get('include_path').'/..');
		drupal_alter('people', $ret);
		//Restore original dir
    chdir($dir);
		return $ret;
	}
	
	/**
	* Get User Id(s) who have specific application
	* Return array of Person Model Objects
	* 
	* @param
	*   $appId application id
	* @return
	*   $peopleWithApp array of person ids
	*/
	public function getPeopleWithApp($appId) {
		$peopleWithApp = array();
		$res = db_query("SELECT user_id FROM user_applications WHERE application_id=%d", $appId);
		while ($row = db_fetch_array($res)) {			
				$peopleWithApp[] = $row['user_id'];
		}
		return $peopleWithApp;
	}
	
	/**
	* To save PersonAppData into database
	*
	* @param
	*   $userId for who data is to be stored
    * @param
	*   $key name of the Appdata 
	* @param
	*   $value value of the Appdata 
	* @param
	*   $appId application to which Appdata belongs to
	*/
	public function setAppData($userId, $key, $value, $appId)
	{
		$user_id = $user_id;
		$app_id = $app_id;
		
		if (empty($value)) {
			if (! db_query("DELETE FROM {application_settings} WHERE application_id = %d AND user_id = %d AND name = '%s'", $appId, $userId, $key)) {
				return false;
			}
		} else {
			if (! db_query("INSERT INTO {application_settings} (application_id, user_id, name, value) VALUES (%d, %d, '%s', '%s') ON DUPLICATE KEY UPDATE value = '%s'", $appId, $userId, $key, $value, $value)) {
				return false;
			}
		}
		return true;
	}
    
	/**
	* To get PersonAppData
	*
	* @param
	*   $ids userIds 
    * @param
	*   $key name of the Appdata 
	* @param
	*   $appId application to which Appdata belongs to
	* @return
	*   $data Appdata
	*/	
	public function getAppData($ids, $keys, $app_id) {
    $data = array();
    $placeholders_ids = array_fill(0, count($ids), "%d");
    if (in_array("@all", $keys)) {
      $res = db_query("SELECT user_id, name, value FROM application_settings WHERE application_id = %d AND user_id IN (" . implode(',', $placeholders_ids) . ")", $app_id, $ids);
    } 
	else {
      $placeholders_keys = array_fill(0, count($keys), "%s");
      $values = array();
      $values[] = $app_id;
      $values = array_merge($values, $ids);
      $values = array_merge($values, $keys);
      $res = db_query("SELECT user_id, name, value FROM application_settings WHERE application_id = %d 
                        AND user_id IN (" . implode(',', $placeholders_ids) . ") 
						AND name IN ('". implode(',', $placeholders_keys )."')", $values);
    }
    while ($app_data = db_fetch_array($res)) {
      $user_id = $app_data['user_id'];
      if (! isset($user_id)) {
        $data[$user_id] = array();
      }
      $key = $app_data['name'];
      $value = $app_data['value'];
      $data[$user_id][$key] = $value;
    }
    return $data;
  }
    
	/**
	* Delete PersonAppData
	*
	* @param
	*   $userId for who data is to be deleted
    * @param
	*   $key name of the Appdata 
	* @param
	*   $appId application to which Appdata belongs to
	*/
	public function deleteAppData($userId, $key, $appId)
	{
		$userId = $userId;
		$appId = $appId;
		if(!db_query("DELETE FROM {application_settings} WHERE application_id = %d AND user_id = %d AND name = '%s'", $appId, $userId, $key)) {
			return false;
		}
		return true;

	}
	
	/**
	* To Store User's Activity in Db
	*
	* @param
	*   $userId for who Activity is to be created
    * @param
	*   $activity activity to be created
	* @param
	*   $appId application to which Activity belongs to
	*/
	public function createActivity($user_id, $activity, $app_id = '0')
	{
		$title = isset($activity['title']) ? trim($activity['title']) : '';
		$body = isset($activity['body']) ? trim($activity['body']) : '';
		$time = time();
		db_query("INSERT INTO activities (id, user_id, app_id, title, body, created) VALUES (0, %d, %d, '%s', '%s', $time)", $user_id, $app_id, $title, $body);
		if (! ($activityId = db_last_insert_id('activities', 'id'))) {
			return false;
		}
		$mediaItems = isset($activity['mediaItems']) ? $activity['mediaItems'] :  array();
		if (count($mediaItems)) {
			foreach ($mediaItems as $mediaItem) {
				$type = isset($mediaItem['type']) ? $mediaItem['type'] : '';
				$mimeType = isset($mediaItem['mimeType']) ? $mediaItem['mimeType'] : '';
				$url = isset($mediaItem['url']) ? $mediaItem['url'] : '';
				$type = trim($type);
				$mimeType = trim($mimeType);
				$url = trim($url);
				db_query("INSERT INTO activity_media_items (id, activity_id, mime_type, media_type, url) VALUES (0, %d, '%s', '%s', '%s')", $activityId, $mimeType, $type, $url);			
			}
		}
		return true;
	}

	/**
	* To get Activities of the User(s)
	*
	* @param
	*   $ids userids - for all activites needs to be fetched
	* @param
	*   $app_id application to which Activity belongs to
	* @param
	*   $sortBy to sort result sets by
	* @param
	*   $filterBy to filter activity results
	* @param
	*   $startIndex start index for pagination of results
    * @param
	*   $count number of results on the page
    * @param
	*   $fields activity fields in the results
	* @return
	*   $activities array of activity objects
	*/
	public function getActivities($ids, $appId, $sortBy, $filterBy, $startIndex, $count, $fields)
	{
		$activities = array();
		foreach ($ids as $key => $val) {
			$ids[$key] = $val;
		}
		$res = db_query("
			SELECT 
				activities.user_id as user_id,
				activities.id as activity_id,
				activities.title as activity_title,
				activities.body as activity_body,
				activities.created as created
			FROM 
				activities
			WHERE
				activities.user_id IN (" . implode(',', $ids) . ")
			ORDER BY 
				created DESC
			");
		while ($row = db_fetch_array($res)) {
			$activity = new Activity($row['activity_id'], $row['user_id']);
			$activity->setStreamTitle('activities');
			$activity->setTitle($row['activity_title']);
			$activity->setBody($row['activity_body']);
			$activity->setPostedTime($row['created']);
			$activity->setMediaItems($this->getMediaItems($row['activity_id']));
			$activities[] = $activity;
		}
		return $activities;
	}

	/**
	* To get Mediaitems of an Activity
	*
	* @param
	*   $activityId for which media items is to fetched
	* @return
	*   $media atcivity media item object  
	*/ 
	private function getMediaItems($activity_id)
	{
		$media = array();
		$activity_id = $activity_id;
		$res = db_query("SELECT mime_type, media_type, url FROM activity_media_items WHERE activity_id = %d", $activity_id);
		while ($row = db_fetch_array($res)) {
			$media[] = new MediaItem(strtoupper($row['mime_type']), strtoupper($row['media_type']), $row['url']);
		}
		return $media;
	}

	/**
	 * Get the set of user id's from a user or collection of users, and group
	 */
	public function getIdSet($user, GroupId $group, SecurityToken $token)
	{
		$ids = array();
		if ($user instanceof UserId) {
			$userId = $user->getUserId($token);
			if ($group == null) {
				return array($userId);
			}
			switch ($group->getType()) {
				case 'all':
				case 'friends':
				case 'groupId':
					$friendIds = ShindigIntegratorDbFetcher::get()->getFriendIds($userId);
					if (is_array($friendIds) && count($friendIds)) {
						$ids = $friendIds;
					}
					break;
				case 'self':
					$ids[] = $userId;
					break;
			}
		} elseif (is_array($user)) {
			$ids = array();
			foreach ($user as $id) {
				$ids = array_merge($ids, $this->getIdSet($id, $group, $token));
			}
		}
		return $ids;
	}


}
?>
