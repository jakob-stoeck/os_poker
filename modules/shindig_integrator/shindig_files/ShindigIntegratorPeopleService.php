<?php
// $Id: ShindigIntegratorPeopleService.php,v 1.2.2.2 2009/08/13 13:16:56 impetus Exp $
/**
 * @file
 * OpenSocial Person Service
 *
 * @see http://incubator.apache.org/shindig/
 * This module contains core shindig server
 */

class ShindigIntegratorPeopleService implements PersonService {

    /**
	* Comparator Function
	* To compare two user names for sorting
	* @param
	*   $person user object
	* @param
	*   $person1 user object
	* @return
	*   -1 if $person is smaller in terms of sorting(asc) else returns 1
	*/ 
	private function comparator($person, $person1)
	{
		$name = $person['name']->getUnstructured();
		$name1 = $person1['name']->getUnstructured();
		if ($name == $name1) {
			return 0;
		}
		return ($name < $name1) ? - 1 : 1;
	}

	/**
	* To Get Person Information 
	*
	* @param
	*   $userId user id to get data 
	* @param
	*   $groupId group of the user user 
	* @param
	*   $fields user object fields
	* @param
	*   $token security token for validation
	* @return
	*   $person person object
	*/ 
	public function getPerson($userId, $groupId, $fields, SecurityToken $token)
	{
		if (! is_object($groupId)) {
			throw new SocialSpiException("Not Implemented", ResponseError::$NOT_IMPLEMENTED);
		}
		$person = $this->getPeople($userId, $groupId, new CollectionOptions(), $fields, $token);
		if (is_array($person->getEntry())) {
			$person = $person->getEntry();
			if (is_array($person) && count($person) == 1) {
				return array_pop($person);
			}
		}
		throw new SocialSpiException("Person not found", ResponseError::$BAD_REQUEST);
	}

    /**
	* To get multiple user's inframation
	*
	* @param
	*   $userId user id to get data 
	* @param
	*   $groupId group of the user user 
    * @param
	*   $options Collection Option object contains other query paramenters 
	* @param
	*   $fields user object fields
	* @param
	*   $token security token for validation
	* @return
	*   $collection containes required results with other options
	*/
	public function getPeople($userId, $groupId, CollectionOptions $options, $fields, SecurityToken $token)
	{
		$sortOrder = $options->getSortOrder();
		$filter = $options->getFilterBy();
		$first = $options->getStartIndex();
		$max = $options->getCount();
		$networkDistance = $options->getNetworkDistance();
		$ids = ShindigIntegratorDbFetcher::get()->getIdSet($userId, $groupId, $token);
		$allPeople = ShindigIntegratorDbFetcher::get()->getPeople($ids, $fields, $options);
		if (! $token->isAnonymous() && $filter == "hasApp") {
			$appId = $token->getAppId();
			$peopleWithApp =  ShindigIntegratorDbFetcher::get()->getPeopleWithApp($appId);
		}
		$people = array();
		foreach ($ids as $id) {
			if ($filter == "hasApp" && ! in_array($id, $peopleWithApp)) {
				continue;
			}
			$person = null;
			if (is_array($allPeople) && isset($allPeople[$id])) {
				$person = $allPeople[$id];
				if (! $token->isAnonymous() && $id == $token->getViewerId()) {
					$person->setIsViewer(true);
				}
				if (! $token->isAnonymous() && $id == $token->getOwnerId()) {
					$person->setIsOwner(true);
				}
				if (! isset($fields['@all'])) {
					$newPerson = array();
					$newPerson['isOwner'] = $person->isOwner;
					$newPerson['isViewer'] = $person->isViewer;
					// these fields should be present always
					$newPerson['displayName'] = $person->displayName;
					$newPerson['name'] = $person->name;
					foreach ($fields as $field) {
						if (isset($person->$field) && ! isset($newPerson[$field])) {
							$newPerson[$field] = $person->$field;
						}
					}
					$person = $newPerson;
				}
				array_push($people, $person);
			}
		}

		if ($sortOrder == 'name') {
			usort($people, array($this, 'comparator'));
		}
		
        try {
          $people = $this->filterResults($people, $options);
        } catch(Exception $e) {
		  $people['filtered'] = 'false';
       }
		
		$totalSize = count($people);
		$collection = new RestfulCollection($people, $options->getStartIndex(), $totalSize);
		$collection->setItemsPerPage($options->getCount());
		return $collection;
	}
	
	private function filterResults($peopleById, $options) {
    if (! $options->getFilterBy()) {
      return $peopleById; // no filtering specified
    }
    $filterBy = $options->getFilterBy();
    $op = $options->getFilterOperation();
    if (! $op) {
      $op = CollectionOptions::FILTER_OP_EQUALS; // use this container-specific default
    }
    $value = $options->getFilterValue();
    $filteredResults = array();
    $numFilteredResults = 0;
    foreach ($peopleById as $id => $person) {
      if ($this->passesFilter($person, $filterBy, $op, $value)) {
        $filteredResults[$id] = $person;
        $numFilteredResults ++;
      }
    }
    return $filteredResults;
  }

  private function passesFilter($person, $filterBy, $op, $value) {
    $fieldValue = $person[$filterBy];
    if (! $fieldValue || (is_array($fieldValue) && ! count($fieldValue))) {
      return false; // person is missing the field being filtered for
    }
    if ($op == CollectionOptions::FILTER_OP_PRESENT) {
      return true; // person has a non-empty value for the requested field
    }
    if (!$value) {
      return false; // can't do an equals/startswith/contains filter on an empty filter value
    }
    // grab string value for comparison
    if (is_array($fieldValue)) {
      // plural fields match if any instance of that field matches
      foreach ($fieldValue as $field) {
        if ($this->passesStringFilter($field, $op, $value)) {
          return true;
        }
      }
    } else {
      return $this->passesStringFilter($fieldValue, $op, $value);
    }
    
    return false;
  }
}