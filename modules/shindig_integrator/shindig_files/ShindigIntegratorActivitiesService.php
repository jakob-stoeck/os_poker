<?php
// $Id: ShindigIntegratorActivitiesService.php,v 1.2.4.1 2009/08/13 08:49:12 impetus Exp $
/**
 * @file
 * OpenSocial Activity Service
 *
 * @see http://incubator.apache.org/shindig/
 * This module contains core shindig server
 */

class ShindigIntegratorActivitiesService implements ActivityService {

    /**
	* To get User's Activity by ID
	*
	* @param
	*   $userId user id to get data 
	* @param
	*   $groupId group of the user  
    * @param
	*   $appId to which activity belongs to 
	* @param
	*   $fields activity object fields
	* @param
	*   $token security token for validation
	* @return
	*   $activity activity object
	*/ 	
	public function getActivity($userId, $groupId, $appdId, $fields, $activityId, SecurityToken $token)
	{
		$activities = $this->getActivities($userId, $groupId, $appdId, null, null, 0, 50, $fields, $token);
		if ($activities instanceof RestFulCollection) {
			$activities = $activities->getEntry();
			foreach ($activities as $activity) {
				if ($activity->getId() == $activityId) {
					return $activity;
				}
			}
		}
		throw new SocialSpiException("Activity not found", ResponseError::$NOT_FOUND);
	}
    
	/**
	* To get All User's Activities
	*
	* @param
	*   $userIds userids - for all activites needs to be fetched
	* @param
	*   $groupId group of the user  
	* @param
	*   $appId application to which Activity belongs to
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
	public function getActivities($userIds, $groupId, $appId, $sortBy, $filterBy, $filterOp, $filterValue, $startIndex, $count, $fields, $activityIds, $token)
	{
		$ids = ShindigIntegratorDbFetcher::get()->getIdSet($userIds, $groupId, $token);
		$activities = ShindigIntegratorDbFetcher::get()->getActivities($ids, $appId, $sortBy, $filterBy, $startIndex, $count, $fields); 
		$totalResults = count($activities);
		$ret = new RestfulCollection($activities, $startIndex, $totalResults);
		$ret->setItemsPerPage($count);
		return $ret;
	}
    
	/**
	* To create User's Activity
	*
	* @param
	*   $userId userid - for who activity needs to be created
	* @param
	*   $groupId group of the user  
	* @param
	*   $appId application to which Activity belongs to
    * @param
	*   $fields activity fields in the results
	* @param
	*   $activty hash of activity fields to be created
    * @param
	*   $token security token for validation
	*/ 
	public function createActivity($userId, $groupId, $appId, $fields, $activity, SecurityToken $token)
	{
		try {
			ShindigIntegratorDbFetcher::get()->createActivity($userId->getUserId($token), $activity, $token->getAppId());
		} catch (Exception $e) {
			throw new SocialSpiException("Invalid create activity request: ".$e->getMessage(), ResponseError::$INTERNAL_ERROR);
		}
	}
	
    /**
	* To delete User's Activity
	*
	* @param
	*   $userId userid - for who activity needs to be created
	* @param
	*   $groupId group of the user  
	* @param
	*   $appId application to which Activity belongs to
    * @param
	*   $fields activity fields in the results
	* @param
	*   $activtyIds array of activity ids that needs to be deleted
    * @param
	*   $token security token for validation
	*/ 
	public function deleteActivities($userId, $groupId, $appId, $activityIds, SecurityToken $token)
	{
		throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
	}
}