<?php
// $Id: ShindigIntegratorAppDataService.php,v 1.1 2008/12/08 07:23:01 impetus Exp $
/**
 * @file
 * OpenSocial AppData Service
 *
 * @see http://incubator.apache.org/shindig/
 * This module contains core shindig server
 */

class ShindigIntegratorAppDataService implements AppDataService {
	
	/**
	* Delete PersonAppData
	*
	* @param
	*   $userId for who data is to be deleted
    * @param
	*   $groupId of the user
	* @param
	*   $appId to which all Appdata belongs to
    * @param
	*   $feilds array of Appdata needs to be deleted 
	* @param
	*   $token security token for validation
	*/
	public function deletePersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
	{
		foreach ($fields as $key) {
			if (! ShindigIntegratorAppDataService::isValidKey($key)) {
				throw new SocialSpiException("The person app data key had invalid characters", ResponseError::$BAD_REQUEST);
			}
		}
		switch ($groupId->getType()) {
			case 'self':
				foreach ($fields as $key) {
					if (! ShindigIntegratorDbFetcher::get()->deleteAppData($userId, $key, $token->getAppId())) {
						throw new SocialSpiException("Internal server error", ResponseError::$INTERNAL_ERROR);
					}
				}
				break;
			default:
				throw new SocialSpiException("Not Implemented", ResponseError::$NOT_IMPLEMENTED);
				break;
		}
		return null;
	}
    
	/**
	* To get Person AppData
	*
	* @param
	*   $userId for who data is to be feched
    * @param
	*   $groupId of the user
	* @param
	*   $appId to which all Appdata belongs to
    * @param
	*   $feilds array of Appdata needs to be fetched 
	* @param
	*   $token security token for validation
	*/ 
	public function getPersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
	{
		$ids = ShindigIntegratorDbFetcher::get()->getIdSet($userId, $groupId, $token);
		$data = ShindigIntegratorDbFetcher::get()->getAppData($ids, $fields, $appId);
		if (!count($data)) {
			throw new SocialSpiException("Data Not Found (app_id = ".print_r($appId, true).") (fields =".print_r($fields, true).")", ResponseError::$NOT_FOUND);
		}
		return new DataCollection($data);
	}
    
	/**
	* To update Person AppData
	*
	* @param
	*   $userId for who data is to be updated
    * @param
	*   $groupId of the user
	* @param
	*   $appId to which all Appdata belongs to
    * @param
	*   $feilds array of Appdata needs to be updated 
    * @param
	*   $values array of new Appdata values needs to be saved 
	* @param
	*   $token security token for validation
	*/ 
	public function updatePersonData(UserId $userId, GroupId $groupId, $appId, $fields, $values, SecurityToken $token)
	{
		foreach ($fields as $key) {
			if (! self::isValidKey($key)) {
				throw new SocialSpiException("The person app data key had invalid characters", ResponseError::$BAD_REQUEST);
			}
		}
		switch ($groupId->getType()) {
			case 'self':
				foreach ($fields as $key) {
					$value = isset($values[$key]) ? $values[$key] : null;
					if (! ShindigIntegratorDbFetcher::get()->setAppData($userId->getUserId($token), $key, $value, $appId)) {
						throw new SocialSpiException("Internal server error", ResponseError::$INTERNAL_ERROR);
					}
				}
				break;
			default:
				throw new SocialSpiException("Not Implemented", ResponseError::$NOT_IMPLEMENTED);
				break;
		}
		return null;
	}

	/**
	 * Determines whether the input is a valid key. Valid keys match the regular
	 * expression [\w\-\.]+.
	 * 
	 * @param key the key to validate.
	 * @return true if the key is a valid appdata key, false otherwise.
	 */
	public static function isValidKey($key)
	{
		if (empty($key)) {
			return false;
		}
		for ($i = 0; $i < strlen($key); ++ $i) {
			$c = substr($key, $i, 1);
			if (($c >= 'a' && $c <= 'z') || ($c >= 'A' && $c <= 'Z') || ($c >= '0' && $c <= '9') || ($c == '-') || ($c == '_') || ($c == '.')) {
				continue;
			}
			return false;
		}
		return true;
	}
}
