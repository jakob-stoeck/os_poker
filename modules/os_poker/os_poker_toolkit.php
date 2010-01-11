<?php // -*- mode: php; tab-width: 2 -*-
//
//    Copyright (C) 2009, 2010 Pokermania
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//


/**
 * Fetch the jpoker application (gadget) ID from the Shindig-Integrator table.
 *
 * @return The record from the {applications} table as an object. Or FALSE if
 *         the application is not registrered in the database.
 */
function	os_poker_get_poker_app_id($forceReload = FALSE)
{
  $application = os_poker_get_poker_application($forceReload);
	return $application ? $application->id : FALSE;
}


/**
 * Fetch the jpoker application (gadget) from the Shindig-Integrator table.
 *
 * @return The record from the {applications} table as an object. Or FALSE if
 *         the application is not registrered in the database.
 */
function os_poker_get_poker_application($refresh = FALSE) {
  static $application = FALSE;
  if(!$application || $refresh) {
    $rs = db_query('SELECT * FROM {applications} WHERE title = \'jpoker\' LIMIT 1');
    if($rs) {
      $application = db_fetch_object($rs);
    }
    else {
      $application = FALSE;
    }
  }
  return $application;
}

function os_poker_set_application_default_settings() {
  $application =& os_poker_get_poker_application();
  if($application) {
    $settings = ! empty($application->settings) ? unserialize($application->settings) : array();
    $defaults = array(
      'os_poker_skin' => url('poker/skin.css', array('absolute' => true)),
    );
    foreach($defaults as $name => $value) {
      if(!is_object($settings[$name])) {
        $settings[$name] = new stdClass();
      }
      if($settings[$name]->default != $value) {
        $settings[$name]->default = $value;
      }
      else {
        unset($defaults[$name]);
      }
    }
    if(count($defaults) > 0) {
      $application->settings = serialize($settings);
      if(drupal_write_record('applications', $application, 'id') == SAVED_UPDATED) {
        foreach($defaults as $name => $value) {
          drupal_set_message(t('Application preference %name set to default value %value.', array('%name' => $name, '%value' => $value)));
        }
      }
    }
  }
  else {
    drupal_set_message('Cannot set default settings for %name application, it doesn\'t exist in the database', array('%name' => 'jpoker'));
  }
}

/*
**
*/

function	os_poker_online_users()
{
	// FROM : light version of /modules/user/user.module : user_block : 3
	$authenticated_count = 0;

	if (user_access('access content'))
	{
		$interval = time() - 60; // User logged in logged more than 1mn are considered as players.
		$authenticated_users = db_query('SELECT COUNT(DISTINCT u.uid) FROM {users} u INNER JOIN {sessions} s ON u.uid = s.uid WHERE s.timestamp >= %d AND s.uid > 0 ORDER BY s.timestamp DESC', $interval);
		$authenticated_count = db_result($authenticated_users);
	}
	return $authenticated_count;}

/*
**
*/	
	
global $os_poker_db_query_override;
$os_poker_db_query_override = "db_query";
function	os_poker_db_query()
{
	global $os_poker_db_query_override;  
	$args = func_get_args();  
	return call_user_func_array($os_poker_db_query_override, $args);}

/*
**
*/

function	_os_poker_nickname_exists($nick, $user_id)
{
	$sql = "SELECT 1
			FROM `{profile_fields}` AS `pf`
			JOIN `{profile_values}` AS `pv` USING(`fid`)
			WHERE `pf`.`name` LIKE 'profile_nickname'
			AND LOWER(`pv`.`value`) LIKE LOWER('%s')
			AND `pv`.`uid` != %d
			LIMIT 1";
	
	$res = os_poker_db_query($sql, $nick, $user_id);
	
	if ($res != FALSE)
	{
		$ex = (bool)db_result($res);
		
		return $ex;}
	
	return TRUE;}

/*
**
*/

function	_os_poker_sort_buddies($a, $b)
{
	$aChips = $a->Chips();
	$bChips = $b->Chips();

	if ($aChips == $bChips)
	{
        return 0; }
	
    return ($aChips < $bChips) ? 1 : -1;
}

/*
**
*/

function	_os_poker_sort_invites($a, $b)
{
	if ($a->created == $b->created)
	{
        return 0; }
	
    return ($a->created < $b->created) ? 1 : -1;
}

/*
**
*/

function	_os_poker_sort_rewards($a, $b)
{
	if ($a["value"] == $b["value"])
	{
        return 0; }
	
    return ($a["value"] < $b["value"]) ? 1 : -1;
}

/*
**
*/

function	_os_poker_rand_player()
{
	$sql = "SELECT MAX(`uid`) FROM `{users}`";
	
	$res= db_query($sql);
	
	if ($res)
	{
		$c = db_result($res);
		
		return "player" . ($c + 1);
	}
	
    return "player";
}

/*
**
*/

function	_os_poker_format_chips($value)
{
	if (empty($value))
	{
		$value = 0;
	}

	if (is_numeric($value))
	{
		$value = number_format($value, 0, ".", ",");
	}
	
	return "$ " . $value;
}

/*
**
*/

function	_os_poker_user_accepts_gifts($u)
{
	$pag = $u->profile_accept_gifts;

	return empty($pag);
}

/*
**
*/

function _os_poker_exec_sql($file)
{
	$results = array();

	if (is_file($file))
	{
		$sql = file_get_contents($file);
		$queries = preg_split("/;\s+/", $sql);

		foreach ($queries as $query)
		{
			if (!empty($query))
			{
				$query = db_prefix_tables($query);
				$results[] = db_query($query);
			}
		}
	}

	return $results;
}
	
?>
