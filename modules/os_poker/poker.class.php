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
					
					if ($chips >= 1000000 || $targets['chips'] >= 1000000)
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
	
	public static function	GiveReward($name, $player, $prewards)
	{
		if ($prewards[$name]["value"] == 0)
		{
			$player->{$name} = time();
			$nChips = $player->Chips();
			$player->chips = $nChips + $prewards[$name]["bonus"];
			$player->Save();

			$args["symbol"] = $prewards[$name]["picture"];
			$args["text"] = t("You just won reward !name : !desc", array("!name" => $prewards[$name]["name"],
																		  "!desc" => $prewards[$name]["desc"])); 
			
			CMessageSpool::instance()->SendMessage($player->uid, $args);
		}
	}
	
	
	public static function	GetRewards() // ... miam :)
	{
		$imagePath = file_directory_path() . "/poker_rewards/";
		$defaultPicture = $imagePath ."reward_default.jpg";
	
		$rewards = 	array(
							"reward1" => array("value" => 0, "name"	=> t("Newcomer"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" =>	t("Player sits down at the table for the first time")),
							"reward2" => array("value" => 0, "name"	=> t("Blind Hen"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins his first hand")),
							"reward3" => array("value" => 0, "name"	=> t("It's taking part that counts!"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player participates in a tourney/sng for the first time")),
							"reward4" => array("value" => 0, "name"	=> t("Bullets"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with AA hole cards")),
							"reward5" => array("value" => 0, "name"	=> t("Cowboys"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with KK hole cards")),
							"reward6" => array("value" => 0, "name"	=> t("Jailhouse Rock"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with QQ hole cards")),
							"reward7" => array("value" => 0, "name"	=> t("Fish-Hooks"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with JJ hole cards")),
							"reward8" => array("value" => 0, "name"	=> t("Route 66"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with 66 hole cards")),
							"reward9" => array("value" => 0, "name"	=> t("Sailboats"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with 44 hole cards")),
							"reward10" => array("value" => 0, "name" => t("Big Slick"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with AK hole cards")),
							"reward11" => array("value" => 0, "name" => t("Big Chick"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with AD hole cards")),
							"reward12" => array("value" => 0, "name" => t("Dinner for two"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins with 69 hole cards")),
							"reward13" => array("value" => 0, "name" => t("Valentines's"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Day Player wins with KQ hole cards, both heart")),
							"reward14" => array("value" => 0, "name" => t("Big Bang 5.000"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins a big pot, more than 5.000 chips")),
							"reward15" => array("value" => 0, "name" => t("No risk no fun"), "color" => "bronce", "points" => 100, "bonus" => 1000, "picture" => $defaultPicture, "desc" => t("Player wins an all-in")),
							"reward16" => array("value" => 0, "name" => t("Be my Buddy I"), "color" => "bronce", "points" => 200, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player has at least 5 buddies")),
							"reward17" => array("value" => 0, "name" => t("Follow me I"), "color" => "bronce", "points" => 200, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player has at least 2 successful invitations")),
							"reward18" => array("value" => 0, "name" => t("Regular's Table"), "color" => "bronce", "points" => 200, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player sits down at the table with at least one buddy")),
							"reward19" => array("value" => 0, "name" => t("It's my round!"), "color" => "bronce", "points" => 200, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player buys a gift for every player at the table, value doesn't matter")),
							"reward20" => array("value" => 0, "name" => t("Challenger"), "color" => "bronce", "points" => 200, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player participates in a challenge")),
							
							"reward21" => array("value" => 0, "name" => t("Jackpot"), "color" => "silver", "points" => 250, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player wins with 777")),
							"reward22" => array("value" => 0, "name" => t("Trips"), "color" => "silver", "points" => 250, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player wins with three of a kind")),
							"reward23" => array("value" => 0, "name" => t("Ten Times Trips"), "color" => "silver", "points" => 250, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins with three of a kind, 10 times")),
							"reward24" => array("value" => 0, "name" => t("Road Testing"), "color" => "silver", "points" => 250, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player wins with a street")),
							"reward25" => array("value" => 0, "name" => t("On The Road"), "color" => "silver", "points" => 250, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins with a street, 10 times")),
							"reward26" => array("value" => 0, "name" => t("Flush"), "color" => "silver", "points" => 250, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player wins with a flush")),
							"reward27" => array("value" => 0, "name" => t("Flush 10x"), "color" => "silver", "points" => 250, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins with a flush, 10 times")),
							"reward28" => array("value" => 0, "name" => t("Full House"), "color" => "silver", "points" => 250, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player wins with a full house")),
							"reward29" => array("value" => 0, "name" => t("Full House 10x"), "color" => "silver", "points" => 250, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins with a full house, 10 times")),
							"reward30" => array("value" => 0, "name" => t("Broadway"), "color" => "silver", "points" => 250, "bonus" => 2500, "picture" => $defaultPicture, "desc" => t("Player wins with AKQJT")),
							"reward31" => array("value" => 0, "name" => t("The Bicycle"), "color" => "silver", "points" => 250, "bonus" => 2500, "picture" => $defaultPicture, "desc" => t("Player wins with A2345")),
							"reward32" => array("value" => 0, "name" => t("Big Bang 10.000"), "color" => "silver", "points" => 250, "bonus" => 2000, "picture" => $defaultPicture, "desc" => t("Player wins a big pot, more than 10.000 chips")),
							"reward33" => array("value" => 0, "name" => t("Big Bang 25.000"), "color" => "silver", "points" => 250, "bonus" => 2500, "picture" => $defaultPicture, "desc" => t("Player wins a big pot, more than 25.000 chips")),
							"reward34" => array("value" => 0, "name" => t("Bad Beat Full House"), "color" => "silver", "points" => 250, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player holds a full house and looses his hand")),
							"reward35" => array("value" => 0, "name" => t("Final Table"), "color" => "silver", "points" => 250, "bonus" => 2500, "picture" => $defaultPicture, "desc" => t("Player places top 9 in a tourney")),
							"reward36" => array("value" => 0, "name" => t("Lounge Lizard"), "color" => "silver", "points" => 500, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player has at least 10 buddies")),
							"reward37" => array("value" => 0, "name" => t("Randy Dandy"), "color" => "silver", "points" => 500, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player has at least 5 successful invitations")),
							"reward38" => array("value" => 0, "name" => t("Regular's Table II"), "color" => "silver", "points" => 500, "bonus" => 2500, "picture" => $defaultPicture, "desc" => t("Player sits down at the table with at least three buddies")),
							"reward39" => array("value" => 0, "name" => t("It's my round! II"), "color" => "silver", "points" => 500, "bonus" => 2500, "picture" => $defaultPicture, "desc" => t("Player buys a gift for every player at the table, aggregate value more than 1.000 Chips")),
							
							"reward40" => array("value" => 0, "name" => t("The Millionaires Club"), "color" => "gold", "points" => 500, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player has a peek chip count of 1.000.000 chips")),
							"reward41" => array("value" => 0, "name" => t("On a Rush"), "color" => "gold", "points" => 500, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins 10 hands in a row during a session")),
							"reward42" => array("value" => 0, "name" => t("Quads"), "color" => "gold", "points" => 500, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins with four of a kind")),
							"reward43" => array("value" => 0, "name" => t("Straight Flush"), "color" => "gold", "points" => 500, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins with a straight flush")),
							"reward44" => array("value" => 0, "name" => t("The Road to Success"), "color" => "gold", "points" => 500, "bonus" => 7500, "picture" => $defaultPicture, "desc" => t("Player wins with a street, 50 times")),
							"reward45" => array("value" => 0, "name" => t("Flush Rush"), "color" => "gold", "points" => 500, "bonus" => 7500, "picture" => $defaultPicture, "desc" => t("Player wins with a flush, 50 times")),
							"reward46" => array("value" => 0, "name" => t("House-Hunter"), "color" => "gold", "points" => 500, "bonus" => 7500, "picture" => $defaultPicture, "desc" => t("Player wins with a full house, 50 times")),
							"reward47" => array("value" => 0, "name" => t("Big Bang 100.000"), "color" => "gold", "points" => 500, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins a big pot, more than 100.000 chips")),
							"reward48" => array("value" => 0, "name" => t("Kamikaze"), "color" => "gold", "points" => 500, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player wins with 27 hole cards, any suit")),
							"reward49" => array("value" => 0, "name" => t("Bad Beat Quads"), "color" => "gold", "points" => 500, "bonus" => 10000, "picture" => $defaultPicture, "desc" => t("Player holds four of a kind and looses his hand")),
							"reward50" => array("value" => 0, "name" => t("Tournament Pro"), "color" => "gold", "points" => 500, "bonus" => 7500, "picture" => $defaultPicture, "desc" => t("Player wins a tourney")),
							"reward51" => array("value" => 0, "name" => t("Cool Dude"), "color" => "gold", "points" => 1000, "bonus" => 7500, "picture" => $defaultPicture, "desc" => t("Player has at least 50 buddies")),
							"reward52" => array("value" => 0, "name" => t("Party Animal"), "color" => "gold", "points" => 1000, "bonus" => 10000, "picture" => $defaultPicture, "desc" => t("Player has at least 25 successful invitations")),
							"reward53" => array("value" => 0, "name" => t("Full table/Private"), "color" => "gold", "points" => 1000, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Table Player sits down at the table with 8 buddies")),
							"reward54" => array("value" => 0, "name" => t("It's my round! III"), "color" => "gold", "points" => 1000, "bonus" => 5000, "picture" => $defaultPicture, "desc" => t("Player buys a gift for every player at the table, aggregate value more than 10.000 Chips")),
							
							"reward55" => array("value" => 0, "name" => t("Royal Family"), "color" => "platinum", "points" => 2000, "bonus" => 10000, "picture" => $defaultPicture, "desc" => t("Player wins with a royal flush")),
							"reward56" => array("value" => 0, "name" => t("Marathon"), "color" => "platinum", "points" => 2000, "bonus" => 15000, "picture" => $defaultPicture, "desc" => t("at least 42 hours at the table within 8 days")),
							"reward57" => array("value" => 0, "name" => t("Strike!"), "color" => "platinum", "points" => 2000, "bonus" => 20000, "picture" => $defaultPicture, "desc" => t("all bronze, silver and goild achievements complete")),
							"reward58" => array("value" => 0, "name" => t("Milestone 10.000"), "color" => "platinum", "points" => 2000, "bonus" => 20000, "picture" => $defaultPicture, "desc" => t("hands played")),
							"reward59" => array("value" => 0, "name" => t("Tournament Expert"), "color" => "platinum", "points" => 2000, "bonus" => 20000, "picture" => $defaultPicture, "desc" => t("Player wins at least 10 tourneys")),
							"reward60" => array("value" => 0, "name" => t("Donald Trump"), "color" => "platinum", "points" => 5000, "bonus" => 25000, "picture" => $defaultPicture, "desc" => t("Player had more than 1 million chips, lost everything and has again > 1 million chips")),
							"reward61" => array("value" => 0, "name" => t("Big Spender"), "color" => "platinum", "points" => 5000, "bonus" => 10000, "picture" => $defaultPicture, "desc" => t("Player buys a gift for every player at the table, aggregate value more than 100.000 Chips")),
							"reward62" => array("value" => 0, "name" => t("Buddy King"), "color" => "platinum", "points" => 5000, "bonus" => 15000, "picture" => $defaultPicture, "desc" => t("Player has at least 100 buddies")),
							"reward63" => array("value" => 0, "name" => t("Invitation King"), "color" => "platinum", "points" => 5000, "bonus" => 25000, "picture" => $defaultPicture, "desc" => t("Player has at least 50 successful invitations")),
							"reward64" => array("value" => 0, "name" => t("Playboy"), "color" => "platinum", "points" => 5000, "bonus" => 25000, "picture" => $defaultPicture, "desc" => t("Player has spent at least 1.000.000 chips for gifts")),
					);
					
		foreach ($rewards as $name => $value)
		{
			if (file_exists($imagePath . $name . ".jpg"))
			{
				$rewards[$name]["picture"] = $imagePath . $name . ".jpg";
			}
		}
					
		return	$rewards;
	}
}       
        

        