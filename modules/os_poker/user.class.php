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


require_once(drupal_get_path('module', 'os_poker') . "/os_poker_toolkit.php");
require_once(drupal_get_path('module', 'os_poker') . "/messages.class.php");

define ("PROFILE_CATEGORY", "Personal information");

/*
**	User class
*/

class CUser
{
	private $_vars = array( );
	private $_user = NULL;
	private	$_isOS = FALSE;
	private $_DUDirty = array();
	private $_OSDirty = array();
	private $_buddiesId = NULL;
	private $_buddiesObj = NULL;
	private $_invites = NULL;
	private $_Tables = NULL;
	private $_Rewards = NULL;
	private $_activeItem = NULL;

	private static $_defaults;

	/*
	**
	*/

	public function __construct( $uid )
	{
		if (user_is_logged_in() == FALSE)
			throw new Exception('Forbidden (User must be logged in)');

		if ($this->Load($uid) == FALSE)
			throw new Exception('Invalid UID');
	}

	/*
	**
	*/

	public static function DefaultValue($key, $default = NULL)
	{
		if (isset(self::$_defaults[$key]))
		{
			return self::$_defaults[$key];
		}
		return $default;
	}

	public static function SetDefaultValue($key, $value)
	{
		$trace = debug_backtrace();

		if (isset($trace[1]['class']) && $trace[1]['class'] == "CUserManager")
		{
			self::$_defaults[$key] = $value;
		}
	}

	/*
	**
	*/

	private function	SetUpProfile()
	{
		require_once(drupal_get_path('module', 'profile') . "/profile.module");

		$result = _profile_get_fields(PROFILE_CATEGORY);

		while ($field = db_fetch_object($result))
		{
			if (empty($this->_user->{$field->name}))
			{
			  $this->_user->{$field->name} = NULL;
			}
		}
	}

	private function 	Load( $uid )
	{
		$this->_user = user_load(array('uid' => $uid));

		if ($this->_user != FALSE)
		{
			$this->SetUpProfile();
			profile_load_profile($this->_user);

			$sql = "SELECT `name`, `value` FROM `{application_settings}` WHERE `application_id`=%d AND `user_id`=%d";

			$res = db_query($sql, os_poker_get_poker_app_id(), $uid);

			if ($res != FALSE)
			{
				$this->_isOS = TRUE;

				while (($row = db_fetch_object($res)) != FALSE)
				{
					$this->_vars[$row->name] = json_decode($row->value, TRUE);
				}
			}
			return TRUE;
		}

		return FALSE;
	}

	public function		Save( )
	{
		$save = FALSE;

		if (count($this->_DUDirty) > 0)
		{
			$toUpdate = array();
      $dirtyProfile = false;
			foreach ($this->_DUDirty as $entry)
			{
        if(strlen($entry) < 8 || substr_compare($entry, 'profile_', 0, 8)!=0) {
          $toUpdate[$entry] = $this->_user->{$entry};
        } else {
          $dirtyProfile = true;
        }
			}
			user_save($this->_user, $toUpdate);
      
      if($dirtyProfile) {
        $profile = array();
        $result = _profile_get_fields(PROFILE_CATEGORY, FALSE);
        while ($field = db_fetch_object($result)) {
          $profile[$field->name] =$this->_user->{$field->name};
        }
        user_save($this->_user, $profile, PROFILE_CATEGORY);
      }

			$save = TRUE;
			$this->_DUDirty = array();
		}

		if (count($this->_OSDirty) > 0)
		{
			$app_id = os_poker_get_poker_app_id();

			foreach ($this->_OSDirty as $key)
			{
				$sql = "REPLACE INTO `{application_settings}` (`application_id`, `user_id`, `name`, `value`)
						VALUES (%d, %d, '%s', '%s')";

				db_query($sql, $app_id, $this->_user->uid, $key, json_encode($this->_vars[$key]));
			}

      if(in_array('money', $this->_OSDirty)) {
        CScheduler::instance()->RegisterTask(new CUpdateUserChipsCount(), $this->_user->uid, array('live'));
      }
			$save = TRUE;
			$this->_OSDirty = array();
		}

		return $save;
	}

	/*
	**
	*/

	public function	IsOSUser( ) { return $this->_isOS; }
	public function	& DrupalUser( ) { return $this->_user; }

	public function	Online()
	{
			return os_poker_user_online($this->uid);
	}

	public function	Chips($formated = FALSE, $locale = 'en_US')
	{
		if (isset($this->_vars["money"]) && isset($this->_vars["money"]["1"]))
		{
		  $chips = $this->_vars["money"]["1"];
		  $chips = bcdiv($chips, 100);
		}
		else
		{
			$chips = self::DefaultValue("chips");
		}

		if ($formated == TRUE)
		{
			$chips = _os_poker_format_chips($chips);
		}

		return $chips;
	}

	private function	SetChips($value)
	{
		if (is_numeric($value))
		{
      $level = $this->Status();
		  $this->_vars["money"] = array("1" => bcmul($value, 100));
			$this->_OSDirty[] = "money";
			CPoker::CheckRewards("chips", $this->_user->uid, array("chips" => $value));
      if($level != $this->Status()) {
        CMessageSpool::instance()->SendInstantMessage(array(
          'text' => t("Your level changed to !level !", array(
            "!level" => $this->Status(),
          )),
          'title' => t('Level'),
        ), $this->uid);
      }
		}
	}

	public function AddChips($value) {
			$nchips = $this->Chips();
			$this->chips = bcadd($nchips, $value);
	}

	public function SubChips($value) {
			$nchips = $this->Chips();
			$this->chips = bcsub($nchips, $value);
	}

	public function Invites($forceReload = FALSE)
	{
		if ($this->_invites == NULL || $forceReload == TRUE)
		{
			$this->_invites = 	array(
										"total" => 0,
										"accepted" => array(),
										"canceled" => array(),
										"pending" => array(),
								);

			$sql = "SELECT  `email`, `uid`, `invitee`, `created`, `expiry`, `joined`, `canceled`, `resent` FROM `{invite}` WHERE `uid` = %d";

			$res = db_query($sql, $this->_user->uid);

			if ($res)
			{
				while (($obj = db_fetch_object($res)))
				{
					if ($obj->canceled != 0)
					{
						$this->_invites["canceled"][] = $obj;
					}
					else if ($obj->joined == 0)
					{
						$this->_invites["pending"][] = $obj;
					}
					else
					{
						$this->_invites["accepted"][] = $obj;
					}

					$this->_invites["total"] = $this->_invites["total"] + 1;
				}
			}
		}

		return $this->_invites;
	}

	public function Items()
	{
		require_once(drupal_get_path('module', 'os_poker') . "/shop.class.php");

		$activated = -1;

		$sql = "SELECT * FROM `{poker_operation}` WHERE `uid` = %d";

		$inventory = array();

		$sql = "SELECT `po`.*, IF(ISNULL(`pue`.`id_operation`), 0, 1) AS active
				FROM `{poker_operation}` AS `po`
				LEFT JOIN `{poker_user_ext}` AS `pue` ON (`po`.`uid` = `pue`.`uid` AND `po`.`id_operation` = `pue`.`id_operation`)
				WHERE `po`.`uid` = %d";

		$res = db_query($sql, $this->_user->uid);

		if ($res) {
			$this->_activeItem = -1;

			while (($obj = db_fetch_object($res))) {
				if ($obj->active == 1) {
					$this->_activeItem = $obj->id_operation;
				}
        try {
          $obj->item = new CItem($obj->id_item);
          $inventory[] = $obj;
        }
        catch (Exception $e) {
          watchdog('OS Poker Shop', 'Error while loading item: "!msg"', array('!msg' => $e->getMessage()), WATCHDOG_ERROR);
        }
			}
		}
		return $inventory;
	}

	public function ActiveItem()
	{
		if ($this->_activeItem == NULL)
		{
			$sql = "SELECT `id_operation` FROM `{poker_user_ext}` WHERE `uid` = %d LIMIT 1";

			$res = db_query($sql, $this->_user->uid);

			if ($res)
			{
				$this->_activeItem = db_result($res);

				if (!$this->_activeItem)
				{
					$this->_activeItem = -1;
				}
			}
		}
		return $this->_activeItem;
	}

  /**
   * Set the active item for this user.
   *
   * @param <type> $id_operation
   * @param <type> $gift If the activation is the result of a gift, the gift
   * details as a structuted array: <code>
   * $gift = array(
   *       'item' => 'name of the item',
   *       'sender' => uid of the user sending the gift,
   *     );
   * </code>
   */
	public function ActivateItem($id_operation, $gift = NULL)
	{
		$sql = "INSERT INTO `{poker_user_ext}` (`uid`, `id_operation`) VALUES (%d, %d)
				ON DUPLICATE KEY UPDATE `id_operation`= %d";

		$res = db_query($sql, $this->_user->uid, $id_operation, $id_operation);
		$this->_activeItem = $id_operation;

    if($gift == NULL) {
      $sql = 'SELECT pi.name FROM {poker_item} as pi LEFT JOIN {poker_operation} as po ON (pi.id_item = po.id_item) WHERE po.id_operation = %d';
      $rs = db_query($sql, $id_operation);
      if($rs) {
        $gift = array(
          'item' => db_result($rs),
          'sender' => $this->_user->uid,
        );
      }
    }
    if($gift) {
      $gift['receiver'] = $this->_user->uid;
      //Enqueue a 'live' gift event to all players sitting at the same table(s) as the receiver
      foreach($this->Tables() as $table) {
        foreach(CPoker::UsersAtTable($table->serial) as $notified_uid) {
          CScheduler::instance()->RegisterTask(new CGiftNotificationMessage(), $notified_uid, array('live'), "-1 day", $gift);
        }
      }
    }
}

	public function LastDailyGift()
	{
		$sql = "SELECT `last_gift` FROM `{poker_user_ext}` WHERE `uid` = %d LIMIT 1";
		$res = db_query($sql, $this->_user->uid);

		if ($res)
		{
			$d = db_result($res);

			if ($d)
			{
				return strtotime($d);
			}
		}
		return FALSE;
	}

	public function CanDailyGift()
	{
		$lg = $this->LastDailyGift();

		return (!$lg || $lg + 86400 <= time()) && count($this->Buddies(TRUE));
	}

	public function DailyGift()
	{
		$sql = "INSERT INTO `{poker_user_ext}` (`uid`, `last_gift`) VALUES (%d, NOW())
				ON DUPLICATE KEY UPDATE `last_gift`= NOW()";

		$result = db_query($sql, $this->_user->uid);

		$buddies = $this->Buddies(TRUE);

    $amount = 100;
		foreach($buddies as $buddy)
		{
			$buddy->AddChips($amount);
			$buddy->Save();

			$args["symbol"] = 'chips';
			$args["text"] = t("You just receive a daily gift from !user", array("!user", $this->profile_nickname));

			CMessageSpool::instance()->SendMessage($buddy->uid, $args);

      //drupal_mail('os_poker', 'daily_gift', $buddy->mail, user_preferred_language($buddy->DrupalUser()), array('amount' => $amount, 'sender' => $this->DrupalUser(), 'account' => $buddy->DrupalUser()));
		}

		return $result;
	}

	public function CompleteProfile()
	{
		$sql = "SELECT `complete_profile` FROM `{poker_user_ext}` WHERE `uid` = %d LIMIT 1";
		$res = db_query($sql, $this->_user->uid);
		return (bool)db_result($res);
	}

	public function SetProfileComplete()
	{
		$sql = "INSERT INTO `{poker_user_ext}` (`uid`, `complete_profile`) VALUES (%d, 1)
				ON DUPLICATE KEY UPDATE `complete_profile`= 1";

		return db_query($sql, $this->_user->uid);
	}

	public function GetProfileCompletePercent()
	{
		$fields_total = 6;
		$fields_count = 1; // nickname necessarily exists
		if (!empty($this->_user->profile_dob["month"]) && // after very first profile, dob is empty, after first profile update submit, it is set by default to user created date so we don't take this into account
				($this->_user->profile_dob["month"] != date("n", $this->_user->created) ||
				$this->_user->profile_dob["day"] != date("j", $this->_user->created) ||
				 $this->_user->profile_dob["year"] != date("Y", $this->_user->created)))
			$fields_count++;
	  if (!empty($this->_user->profile_gender))
			$fields_count++;
	  if (!empty($this->_user->profile_city))
			$fields_count++;
	  if (!empty($this->_user->profile_country))
			$fields_count++;
	  if (!empty($this->_user->picture))
			$fields_count++;
		$percent = round($fields_count * 100 / $fields_total);

		return ($percent);
	}

	public function	Rewards($forceReload = FALSE)
	{
		if ($this->_Rewards == NULL || $forceReload == TRUE)
		{
			require_once(drupal_get_path('module', 'os_poker') . "/poker.class.php");

			$this->_Rewards = CPoker::GetRewards();

			foreach ($this->_Rewards as $key => $value)
			{
				if (isset($this->_vars[$key]))
				{
					$this->_Rewards[$key]["value"] = $this->_vars[$key];
				}
			}
		}

		return $this->_Rewards;
	}

	public function GetLastReward()
	{
	  $sql = "SELECT `name` FROM `{application_settings}` WHERE `name` LIKE 'reward%' AND `user_id` = %d ORDER BY `value` DESC";
	  $res = db_query($sql, $this->_user->uid);

	  $reward_key = NULL;

	  if ($res)
	    {
	      $reward_key = mysql_fetch_row($res);
	      $reward_key = $reward_key[0];
	    }
	  return $reward_key;
	}

	public function GetNumRewards()
	{
	  $sql = "SELECT COUNT(`name`) as num_rewards FROM `{application_settings}` WHERE `name` LIKE 'reward%' AND `user_id` = %d";
	  $res = db_query($sql, $this->_user->uid);

	  $num_rewards = NULL;

	  if ($res)
	    {
	      $num_rewards = mysql_fetch_row($res);
	      $num_rewards = $num_rewards[0];
	    }
	  return $num_rewards;
	}

	public function Status()
	{
    $status = CPoker::GetStatus();
    $chips = $this->Chips();
    foreach($status as $value => $name) {
			if(bccomp($chips, $value) >= 0) {
        return $name;
      }
    }
    return $status[0];
	}

	public function StatusEx(&$level, &$maxlevel)
	{
			$status = CPoker::GetStatus();
			$chips = $this->Chips();
	    $level = $maxlevel = count(array_keys($status));
			foreach($status as $value => $name) {
					if(bccomp($chips, $value) >= 0) {
							return $name;
					}
					$level--;
			}
			return $status[0];
	}


	public function	Tables($forceReload = FALSE)
	{
		if ($this->_Tables == NULL || $forceReload == TRUE)
		{
			require_once(drupal_get_path('module', 'os_poker') . "/poker.class.php");

			$this->_Tables = CPoker::FindUserTable($this->_user->uid);
		}

		return $this->_Tables;
	}

	public function	Buddies( $returnObject = FALSE, $forceReload = FALSE )
	{
		if ($this->_buddiesId == NULL || $forceReload == TRUE)
		{
			require_once(drupal_get_path('module', 'user_relationships_api') . "/user_relationships_api.module");

			$args = array('user' => $this->_user->uid, 'approved' => TRUE);
			$relationship_type = user_relationships_type_load(array("name" => "buddy"));
			$query = _user_relationships_generate_query($args, array('include_user_info' => FALSE));
			$results = db_query($query['query'], $query['arguments']);

			$this->_buddiesObj = NULL;
			$this->_buddiesId = array();

			while ($relationship = db_fetch_object($results))
			{
				$this_user_str  = (($this->_user->uid == $relationship->requestee_id) ? 'requester_id' : 'requestee_id');
				$this_user      = $relationship->{$this_user_str};

				$this->_buddiesId[] = $this_user;
			}
		}

		if ($returnObject == FALSE)
		{
			return $this->_buddiesId;
		}

		if ($this->_buddiesObj == NULL)
		{
			$this->_buddiesObj = CUserManager::instance()->UserList($this->_buddiesId, $forceReload);
		}


		usort($this->_buddiesObj, "_os_poker_sort_buddies");

		return $this->_buddiesObj;
	}


	public function BuddyRequested($uid)
	{
		require_once(drupal_get_path('module', 'user_relationships_api') . "/user_relationships_api.module");

		$args = array('user' => $this->_user->uid, 'approved' => FALSE);
		$relationship_type = user_relationships_type_load(array("name" => "buddy"));
		$query = _user_relationships_generate_query($args, array('include_user_info' => FALSE));
		$results = db_query($query['query'], $query['arguments']);

		while ($relationship = db_fetch_object($results))
		{
			if ($relationship->requestee_id == $uid || $relationship->requester_id == $uid)
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	public function __get( $key )
	{
		if (isset($this->_user->{$key}) && !empty($this->_user->{$key})) {
			return $this->_user->{$key};
    }
		if (isset($this->_vars[$key]) && !empty($this->_vars[$key])) {
      return $this->_vars[$key];
    }
		return self::DefaultValue($key);
	}

	public function __set( $key, $value )
	{
		switch ($key)
		{
			case "chips":
				$this->SetChips($value);
			break;

			default:
				if (isset($this->_vars[$key]) || (strlen($key) > 6 && !substr_compare($key, "reward", 0, 6))) //exception for rewards
				{
					$this->_OSDirty[] = $key;
					$this->_vars[$key] = $value;
				}
				else
				{
					$this->_DUDirty[] = $key;
					$this->_user->{$key} = $value;
				}
			break;
		}
	}

}

/*
** User management class
*/

class CUserManager
{
	private static $_instance;

	private $_users = array();
	private $_forcedUser = NULL;

	/*
	**
	*/

	protected function __construct()
	{
		//CUser::SetDefaultValue("picture", drupal_get_path("theme", "poker") . "/images/picture-default.png");
		CUser::SetDefaultValue("BiggestPotWon", t("N/A"));
		CUser::SetDefaultValue("BestHand", t("N/A"));
		CUser::SetDefaultValue("HandsPlayed", t("N/A"));
		CUser::SetDefaultValue("HandsWon", t("N/A"));
	}

	public static function instance($forceReload = FALSE)
	{
	    if (!self::$_instance instanceof self || $forceReload == TRUE)
	    {
			self::$_instance = new self;
	    }
	    return self::$_instance;
	}

	/*
	**
	*/

	public function	SearchUsers($params, $offset = 0, $limit = NULL)
	{
		$sql = "SELECT `u`.`uid`
				FROM `{users}` AS `u`
				JOIN `{profile_values}` AS `pv` USING(`uid`)
				JOIN `{profile_fields}` AS `pf` USING (`fid`) ";


		$where = array();
		$pfields = array('profile_gender', 'profile_city', 'profile_country');
		$ufields = array("mail");

		$val = array();
		$userfields = 0;

		foreach ($params as $field_n => $field_v)
		{
			$field_v = trim($field_v);

			if ($field_n == "profile_nickname" && !empty($field_v))
			{
				$where[] = "((`pf`.`name` LIKE '{$field_n}') AND (LOWER(`pv`.`value`) LIKE LOWER('%%%s%%')))";
				$val[] = $field_v;
			}
			if (in_array($field_n, $pfields) && !empty($field_v))
			{
				$where[] = "((`pf`.`name` LIKE '{$field_n}') AND (LOWER(`pv`.`value`) LIKE LOWER('%s')))";
        $val[] = $field_v;
			}
      if($field_n == 'level') {
        if($field_v >= 0) {
          $sql .= 'JOIN {application_settings} AS appdata ON user_id = uid ';
          $where[] = "(appdata.application_id = %d) AND (appdata.name = '%s') AND (CAST(SUBSTRING(appdata.value,6) AS DECIMAL) >= %d) AND appdata.value not like '%\"1\":\"%' ";
          $where[] = "(appdata.application_id = %d) AND (appdata.name = '%s') AND (CAST(SUBSTRING(appdata.value,7) AS DECIMAL) >= %d) AND appdata.value like '%\"1\":\"%' ";
          $val[] = os_poker_get_poker_app_id();
          $val[] = 'money';
          $val[] = bcmul($field_v, 100);
          $val[] = os_poker_get_poker_app_id();
          $val[] = 'money';
          $val[] = bcmul($field_v, 100);
        }
      }
			else if (in_array($field_n, $ufields) && !empty($field_v))
			{
				$where[] = "((`u`.`{$field_n}`) LIKE '%s')";
				$val[] = $field_v;
				++$userfields;
			}
		}

		$nwhere = count($where);

		$sql .= " WHERE `u`.`uid` != 0";

		if ($nwhere > 0)
		{
			$sql .= " AND (" . implode(" OR ", $where) . ")";
			$val[] = $nwhere;
		}

		$sql .= " GROUP BY `u`.`uid`";

		if ($nwhere > 0 && ($nwhere != $userfields))
		{
		        $nwhere -= $userfields;
			$sql .= " HAVING COUNT(*) >= %d";
		}

		$sql .= " ORDER BY `u`.`uid`";

		if ($limit != NULL)
		{
			$sql .= " LIMIT {$offset}, {$limit}";
		}

		$res = db_query($sql, $val);

		if ($res)
		{
			$found = array();

			while (($obj = db_fetch_object($res)))
			{

				if (isset($params["online_only"]))
				{
					//FIXME : GORE ! it will be better to do a cross db request instead
					$usr = CUserManager::instance()->User($obj->uid);

					if ($usr->Online())
					{
						$found[] = $obj->uid;
					}
				}
				else
				{
					$found[] = $obj->uid;
				}
			}


			return $found;
		}

		return NULL;
	}


	public function UserList($uidArray, $forceReload = FALSE)
	{
		$ulist = array();

		foreach ($uidArray as $uid)
		{
			if (is_numeric($uid))
			{
				$user = $this->User($uid, $forceReload);

				if ($user != NULL)
				{
					$ulist[] = $user;
				}
			}
			else if (is_object($uid) && get_class($uid) == "CUser")
			{
				$ulist[] = $uid;
			}
			else if (is_object($uid) && isset($uid->uid) && is_numeric($uid->uid))
			{
				$user = $this->User($uid->uid, $forceReload);

				if ($user != NULL)
				{
					$ulist[] = $user;
				}
			}
			else
			{
				throw new Exception(t('Requested user is not a uid or a CUser'));
			}
		}

		return $ulist;
	}

	public function	CurrentUser($forceReload = FALSE)
	{
		if ($this->_forcedUser == NULL)
		{
			global $user;

			return $this->User($user->uid, $forceReload);
		}
		else
		{
			return $this->User($this->_forcedUser, $forceReload);
		}
	}

	/*
	** Debug helpers, only if you know what you are doing
	*/

	public function	DebugForceCurrentUser($uid)
	{
		$this->_forcedUser = $uid;
	}

	public function	DebugRestoreCurrentUser()
	{
		$this->_forcedUser = NULL;
	}

	/*
	**
	*/

	public function	User($uid, $forceReload = FALSE)
	{
		$user = NULL;

		if ($forceReload == FALSE && isset($this->_users[$uid]))
		{
			$user = $this->_users[$uid];
		}
		else
		{
			try
			{
				$user = new CUser($uid);
				$this->_users[$user->uid] = $user;
			}
			catch (Exception $e)
			{
				$user = NULL;
			} //TODO: Debug output ?
		}

		return $user;
	}
}

class CUpdateUserChipsCount extends CMessage {
	public function Run($context_user, $arguments) {
    //force reload of current user to ensure chips count is updated.
    $context_user = CUserManager::instance()->User($context_user->uid, true);
    $msg = array(
      'type' => 'os_poker_update_chips',
      'body' => array(
        'amount' => $context_user->Chips(),
      )
    );
    parent::Run($context_user, $msg);
	}
	public function MaxInstances() {
    return 1;
  }
}

class CTourneyNotificationMessage extends CMessage {
		public function Run($context_user, $arguments) {
				list($tourney_serial, $tourney_name, $table_serial) = $arguments;

				//force reload of current user to ensure chips count is updated.
				$context_user = CUserManager::instance()->User($context_user->uid, true);

				$msg = array(
						'type' => 'os_poker_tourney_start',
						'body' => array(
								'tourney_id' => $tourney_serial,
								'tourney_name' => $tourney_name,
								'table_id' => $table_serial,
								)
						);
				parent::Run($context_user, $msg);
		}

		public function MaxInstances() {
				return 1;
		}
}

class CGiftNotificationMessage extends CMessage {
  public function Run($context_user, $arguments) {
    $msg = array(
      'type' => 'os_poker_gift',
      'body' => array(
        'gift' => isset($arguments['item']) ? $arguments['item'] : t('Unknown'),
        'cls' => os_poker_clean_css_identifier(isset($arguments['item']) ? $arguments['item'] : 'Unknown'),
        'to_uid' => $arguments['receiver'],
        'from_uid' =>$arguments['sender'],
      )
    );
    parent::Run($context_user, $msg);
	}
}
?>
