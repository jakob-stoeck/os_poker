<?php

function	os_poker_get_poker_app_id($forceReload = FALSE)
{
	static	$app_id = FALSE;
	
	if ($forceReload == TRUE)
        {
		$app_id = FALSE;
	}

	if ($app_id == FALSE)
	{
		$sql = "SELECT `id` FROM `{applications}` WHERE `title` = 'jpoker' LIMIT 1";
		$res = db_query($sql);
		
		if ($res != FALSE)
		{
			$app_id = db_result($res);
		}
	}
	
	return $app_id;}

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
	
?>
