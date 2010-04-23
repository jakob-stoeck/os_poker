<?php // -*- mode: php; tab-width: 2 -*-
//
//    Copyright (C) 2009, 2010 Pokermania
//    Copyright (C) 2010 OutFlop
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

require_once(drupal_get_path('module', 'os_poker') . "/user.class.php");
require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");
		
define ("POKER_DB", "pythonpokernetwork");

		
/*
**
*/
		
class CPoker
{
	private static $_pokerDB = POKER_DB;
	
	
	public static function ChangePokerDB($name)
	{
		$old = self::$_pokerDB;
		self::$_pokerDB = $name;
		return $old;
	}

	public static function UsersAtTable($table_id, $returnObjects = FALSE)
	{
		$users = array();
		
		$lastDb = db_set_active(self::$_pokerDB);
	
		$sql = "SELECT `user_serial` FROM `user2table` WHERE `table_serial` = %d";
		
		$res = db_query($sql, $table_id);
		
		if ($res)
		{
			while (($u = db_result($res)))
			{
				$users []= $u;
			}
		}
		
		db_set_active($lastDb);
		
		if ($returnObjects != FALSE)
		{
			return CUserManager::instance()->UserList($users);
		}
		
		return $users;
	}
	
	public static function FindUserTable($user_id)
	{
		$tables = array();
	
		$lastDb = db_set_active(self::$_pokerDB);
		
		$sql = "SELECT `t`.`serial`, `t`.`name` FROM `pokertables` AS `t` JOIN `user2table` AS `u` ON (`u`.`table_serial` = `t`.`serial`) WHERE `u`.`user_serial` = %d";
		
		$res = db_query($sql, $user_id);
		
		if ($res)
		{
			while (($t = db_fetch_object($res)))
			{
				$tables []= $t;
			}
		}
		
		db_set_active($lastDb);

		return $tables;
	}

	// Return list of user ids for players that are sitting at a table
	public static function PlayingUsers() {
			$lastDb = db_set_active(self::$_pokerDB);

			$sql = "SELECT distinct `user2table`.user_serial from `user2table`";
			$res = db_query($sql);
			$users = array();
			if ($res) {
					while ($ut = db_fetch_object($res)) {
							$users[] = $ut->user_serial;
					}
			}

			db_set_active($lastDb);
			return $users;
	}

	public static function ActiveTourneysForUser($user_id) {
		$lastDb = db_set_active(self::$_pokerDB);
	
		$sql = "SELECT `tourney_serial`, `table_serial`, `tourneys`.`name` FROM `user2tourney` LEFT JOIN `tourneys` ON (`user2tourney`.`tourney_serial` = `tourneys`.`serial`) WHERE `user_serial` = %d and `tourneys`.`state` = 'running' and `table_serial` != -1";
		$res = db_query($sql, $user_id);

		$tourneys = array();
		if ($res) {
				while (($t = db_fetch_object($res)))
				{
						$tourneys []= $t;
				}
		}

		db_set_active($lastDb);
		
		return $tourneys;
	}

	public static function BestRankingsForUser($user_id, $limit = 20) {
		$lastDb = db_set_active(self::$_pokerDB);
	
		$sql = "SELECT * FROM `user2tourney` LEFT JOIN `tourneys` ON (`user2tourney`.`tourney_serial` = `tourneys`.`serial`) WHERE `user_serial` = %d and `tourneys`.`state` = 'complete' AND `user2tourney`.`rank` is not null ORDER by `user2tourney`.`rank` limit %d";
		$res = db_query($sql, $user_id, $limit);

		$tourneys = array();
		if ($res) {
				while (($t = db_fetch_object($res)))
				{
						$tourneys []= $t;
				}
		}

		db_set_active($lastDb);
		
		return $tourneys;
	}

	public static function RegisteredTourneysForUser($user_id, $limit = 20) {
		$lastDb = db_set_active(self::$_pokerDB);
	
		$sql = "SELECT * FROM `user2tourney` LEFT JOIN `tourneys` ON (`user2tourney`.`tourney_serial` = `tourneys`.`serial`) WHERE `user_serial` = %d and `tourneys`.`state` = 'registering' ORDER by `tourneys`.`start_time` LIMIT %d";
		$res = db_query($sql, $user_id, $limit);

		$tourneys = array();
		if ($res) {
				while (($t = db_fetch_object($res)))
				{
						$tourneys []= $t;
				}
		}

		db_set_active($lastDb);
		
		return $tourneys;
	}

	public static function TourneyRegisteredUsers($tourney_id, $limit = 20) {
		$lastDb = db_set_active(self::$_pokerDB);
	
		$sql = "SELECT * FROM `user2tourney` LEFT JOIN `tourneys` ON (`user2tourney`.`tourney_serial` = `tourneys`.`serial`) WHERE `tourney_serial` = %d LIMIT %d";
		$res = db_query($sql, $tourney_id, $limit);

		$tourneys = array();
		if ($res) {
				while (($t = db_fetch_object($res)))
				{
						$tourneys []= $t;
				}
		}

		db_set_active($lastDb);
		
		return $tourneys;
	}

	public static function	CheckRewards($action, $source, $targets)
	{
		$player = CUserManager::instance()->User($source);
		
		if ($player)
		{
			$rew = $player->Rewards();
		
			switch ($action)
			{
				case "invite":
				
					$invites = $player->Invites();
					
					if (count($invites["accepted"]) >= 50)
					{
						CPoker::GiveReward("reward63", $player, $rew);
					}
					if (count($invites["accepted"]) >= 25)
					{
						CPoker::GiveReward("reward52", $player, $rew);
					}
					if (count($invites["accepted"]) >= 5)
					{
						CPoker::GiveReward("reward37", $player, $rew);
					}
					if (count($invites["accepted"]) >= 2)
					{
						CPoker::GiveReward("reward17", $player, $rew);
					}
		
				break;

				case "buddy":
				
					$buddies = $player->Buddies(FALSE, TRUE);
					
					if (count($buddies) >= 100)
					{
						CPoker::GiveReward("reward62", $player, $rew);
					}
					if (count($buddies) >= 50)
					{
						CPoker::GiveReward("reward51", $player, $rew);
					}
					if (count($buddies) >= 10)
					{
						CPoker::GiveReward("reward36", $player, $rew);
					}
					if (count($buddies) >= 5)
					{
						CPoker::GiveReward("reward16", $player, $rew);
					}
		
				break;
				
				case "sit":
				
					$buddies = $player->Buddies();
				
					CPoker::GiveReward("reward1", $player, $rew);
		
					if ($rew["reward18"]["value"] == 0)
					{
						foreach ($buddies as $buddy)
						{
							if (in_array($buddy, $targets))
							{
								CPoker::GiveReward("reward18", $player, $rew);
							}
						}
					}
		
				break;
			
				case "chips":
					$chips = $player->Chips();

					if (bccomp($chips, "1000000") >= 0 ||
							bccomp($targets['chips'], "1000000") >= 0) 
					{
						CPoker::GiveReward("reward40", $player, $rew);
					}
				break;

				default: break;
			}

			/* global check at each CheckRewards for Strike! */
			$all_rewards = CPoker::GetRewards();
			if ($player->GetNumRewards() == count($all_rewards) - 1)
				{
					CPoker::GiveReward("reward57", $player, $rew);
				}
		}
	}
	
	public static function	GiveReward($name, $player, $prewards) {
		if ($prewards[$name]["value"] == 0) {
			$player->{$name} = time();
			$player->AddChips($prewards[$name]["bonus"]);
			$player->Save();

			$args["symbol"] = $prewards[$name]["picture"];
			$args["text"] = t("You just won reward !name : !desc", array(
        "!name" => $prewards[$name]["name"],
				"!desc" => $prewards[$name]["desc"]
      ));
			
			CMessageSpool::instance()->SendMessage($player->uid, $args);
      CMessageSpool::instance()->SendInstantMessage(array(
        'text' => '<div class="poker_reward_'.os_poker_clean_css_identifier($name).'">' . $args["text"] . '</div>',
        'title' => t('Reward'),
      ), $player->uid);
		}
	}

	public static function	ShowReward($name, $player_uid, $prewards) {
			$args["symbol"] = $prewards[$name]["picture"];
			$args["text"] = t("You just won reward !name : !desc", array(
        "!name" => $prewards[$name]["name"],
				"!desc" => $prewards[$name]["desc"]
      ));

			CMessageSpool::instance()->SendSystemMessage($player_uid, $args);
      CMessageSpool::instance()->SendInstantMessage(array(
        'text' => '<div class="poker_reward_'.os_poker_clean_css_identifier($name).'">' . $args["text"] . '</div>',
        'title' => t('Reward'),
      ), $player_uid);
			
			return $args;
	}
	
	
	public static function	GetRewards() // ... miam :)
	{
		$imagePath = file_directory_path() . "/poker_rewards/";
		$defaultPicture = $imagePath ."reward_default.jpg";
	
		include_once(drupal_get_path('module', 'os_poker') . "/rewards.lib.php");

		$rewards = 	os_poker_get_all_rewards();
					
		foreach ($rewards as $name => $value)
		{
			if (file_exists($imagePath . $name . ".gif"))
			{
				$rewards[$name]["picture"] = $imagePath . $name . ".gif";
			} else {
					$rewards[$name]["picture"] = $defaultPicture;
			}
		}
					
		return	$rewards;
	}

  public static function GetStatus() {
    static $status = NULL;
    if(!isset($status)) {
      $status = array(
        10000000 => t('Rockefeller'),
        5000000 => t("Highroller"),
        2500000 => t("Shark"),
        1000000 => t("Big Rock"),
        500000 => t("Stone Face"),
        250000 => t("Pokermaniac"),
        100000 => t("Chip Hunter"),
        50000 => t("Rising Star"),
        10000 => t("Chippy"),
        0 => t("Fish"),
      );
    }
    return $status;
  }
}   
        

        
