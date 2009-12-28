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


require_once(drupal_get_path('module', 'os_poker') . "/poker.class.php");
require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");

/*
**
*/

function	os_poker_poll_messages()
{
	$GLOBALS['conf']['cache'] = CACHE_DISABLED;

	if (function_exists('set_time_limit'))
	{
		//script execution time fixed to 2mn
		@set_time_limit(120);
	}

	$current_user = CUserManager::instance()->CurrentUser();
	$resp = array(
					"errorMsg" => NULL,
					"error" => FALSE,
					"messages" => array(),
				);

	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');

	if ($current_user && $current_user->uid != 0)
	{

		//process takes only 1mn to avoid js / php timeout
		for ($pass = 0; $pass < 20; ++$pass)
		{
			if (CScheduler::instance()->IsNewTask())
			{
				CScheduler::instance()->ReloadTasks();
				CScheduler::instance()->Trigger("inbox");
				$mbox = CScheduler::instance()->GetTasks("inbox");
				$mboxsize = count($mbox);
				$resp["messages"][] = array("type" => "os_poker_messagebox", "body" => array("inbox" => $mboxsize,
																							 "picture" => drupal_get_path('module', 'os_poker') . "/images/mailbox.png"));
			}

			CScheduler::instance()->Trigger("live"); //Trigger live, and fill message spooler

			$messages = CMessageSpool::instance()->Get();

			foreach ($messages as $msg)
			{
				$resp["messages"][] = $msg;
			}

			CMessageSpool::instance()->Flush();

			if (count($resp["messages"]) > 0)
			{
				return json_encode($resp);
			}

			sleep(3); //poll every 3 sec
		}
	}
	else
	{
		sleep(60);
	}

	if (count($resp["messages"]) == 0)
	{
		$resp["messages"][] = array("type" => "noop", "body" => NULL);
	}

	return json_encode($resp);
}

/*
**
*/

function	os_poker_process_message()
{
	$current_user = CUserManager::instance()->CurrentUser();
	$resp = array(
					"errorMsg" => NULL,
					"error" => FALSE,
					"messages" => array(),
				);

	if ($current_user && $current_user->uid != 0)
	{
		$message_type = (isset($_GET["type"]) ? $_GET["type"] : "noop");

		switch($message_type)
		{
			case "os_poker_sit_down":
				CheckRewards("sit", $current_user->uid, json_decode($_GET["players"], TRUE));
				$resp["messages"][] = array("type" => "noop", "body" => NULL);
			break;

			case "os_poker_daily_gift":
				if ($current_user->CanDailyGift())
				{
					$current_user->DailyGift();
				}
				$resp["messages"][] = array("type" => "noop", "body" => NULL);
			break;

			case "os_poker_load_messagebox":
				CScheduler::instance()->Trigger("inbox");
				$mbox = CScheduler::instance()->GetTasks("inbox");
				$mboxsize = count($mbox);
				if ($_GET["msgcount"] != $mboxsize)
				{
					$resp["messages"][] = array("type" => "os_poker_messagebox", "body" => array("inbox" => $mboxsize,
																								 "picture" => drupal_get_path('module', 'os_poker') . "/images/mailbox.png"));
				}
				else
				{
					$resp["messages"][] = array("type" => "noop", "body" => NULL);
				}
			break;

			case "os_poker_challenge_user":
				//TODO: limit challenge to 1 per sender/receiver
				if (isset($_GET["challengetarget"]))
				{
					$target_user = CUserManager::instance()->User($_GET["challengetarget"]);

					if ($target_user && $target_user->uid != 0 && $current_user->uid != $target_user->uid)
					{
						//Wait for symbol, text, link
						$args["symbol"] = 'chips';
						$args["text"] = t("You just receive a headsup challenge from !user", array("!user" => $current_user->profile_nickname));
						$args["links"] = "<a class='noreplace' href='javascript:void(0);' onclick='javascript:parent.os_poker_start_challenge(" . $current_user->uid . ", " . $target_user->uid. ");'>" . t("Accept") . "</a>/<a href='javascript:void(0);' >" . t("Refuse") . "</a>";

						CMessageSpool::instance()->SendMessage($target_user->uid, $args);
						CMessageSpool::instance()->SendInstantMessage(array("text" => t("You just challenged !user", array("!user" => $target_user->profile_nickname))));
					}
				}
			break;

			case "os_poker_activate_item":
				if (isset($_GET["id_item"]) && is_numeric($_GET["id_item"]))
				{
					$current_user->ActivateItem($_GET["id_item"]);
				}
			break;

			case "os_poker_invite_user":
				if (isset($_GET["target"]))
				{
					$target_user = CUserManager::instance()->User($_GET["target"]);
					if ($target_user && $target_user->uid != 0 && $current_user->uid != $target_user->uid)
					{
						$tables = $current_user->Tables();

						if (count($tables) > 0)
						{
							$args["symbol"] ='chips';
							$args["text"] = t("!user is playing at table !table come and join", array("!user" => $current_user->profile_nickname, "!table" => $tables[0]->name));

							//TODO : Check $_GET["online"] to send mail
							CMessageSpool::instance()->SendMessage($target_user->uid, $args);
							CMessageSpool::instance()->SendInstantMessage(array("text" => t("Invitation sent to !user", array("!user" => $target_user->profile_nickname))));
						}
					}
				}
			break;

			case "HAND":
			default:
				$resp["messages"][] = array("type" => "noop", "body" => NULL);
			break;
		}
	}
	else
	{
		$resp["messages"][] = array("type" => "noop", "body" => NULL);
	}

	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');

	return json_encode($resp);
}


?>