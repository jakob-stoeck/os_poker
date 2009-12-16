<?php

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
	
	header('Cache-Control: no-cache, must-revalidate');
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
				//CScheduler::instance()->Trigger("inbox");
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
						$args["symbol"] = drupal_get_path('module', 'os_poker') . "/images/msg_chips.gif";
						$args["text"] = t("You just receive a headsup challenge from !user", array("!user", $current_user->profile_nickname));
						$args["links"] = "<a href='javascript:void(0);' >" . t("Accept") . "</a>/<a href='javascript:void(0);' >" . t("Refuse") . "</a>";
						
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

	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	
	return json_encode($resp);
}


?>