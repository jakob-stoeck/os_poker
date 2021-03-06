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

require_once(drupal_get_path('module', 'os_poker') . "/poker.class.php");
require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");

/*
**
*/

function os_poker_poll_messages()
{
  //Disable session writing,
  //see http://drupal-dev.pokersource.info/trac/ticket/37
  session_save_session(FALSE);
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
				$mbox = CScheduler::instance()->GetUnreadTasks("inbox");
				$mboxsize = count($mbox);
				$resp["messages"][] = array("type" => "os_poker_messagebox", "body" => array("inbox" => $mboxsize,
																							 "picture" => $base_path . drupal_get_path('module', 'os_poker') . "/images/mailbox.png"));
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

function os_poker_process_message() {
  set_error_handler('_os_poker_process_message_error_handler');
  set_exception_handler('_os_poker_process_message_exception_handler');
  _os_poker_process_message_unsafe();
  restore_error_handler();
  restore_exception_handler();
}

function _os_poker_process_message_error_handler($errno, $errstr) {
  switch($errno) {
    case E_ERROR:
    case E_USER_ERROR:
      $resp = array(
        'errorMsg' => $errstr,
        'error' => TRUE,
        'messages' => array('type' => 'noop', 'body' => NULL),
      );
      _os_poker_process_message_set_header();
      die(json_encode($resp));
      return true;
      break;
  }
}

function _os_poker_process_message_exception_handler($exception) {
  $resp = array(
    'errorMsg' => $exception->getMessage(),
    'error' => TRUE,
    'messages' => array('type' => 'noop', 'body' => NULL),
  );
  _os_poker_process_message_set_header();
  die(json_encode($resp));
}

function _os_poker_process_message_set_header() {
  header('Cache-Control: no-store, no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json');
}

function	_os_poker_process_message_unsafe()
{
	static $poker_server_events =  array('NONE', 'HAND', 'TOURNEY', 'BUY_IN', 'REFILL', 'PRIZE', 'REGISTER', 'UNREGISTER', 'LEAVE', 'SEAT', 'TOURNEY_START');
	$current_user = CUserManager::instance()->CurrentUser();
	$resp = array(
		"errorMsg" => NULL,
		"error" => FALSE,
		"messages" => array(),
		);

	$message_type = trim(isset($_GET["type"]) ? trim($_GET["type"]) : "noop");

	if ($message_type == "os_poker_reward") {
		/* Reward message is a special message which comes without a current user context 
		   args[0] = reward number
		   args[1] = player serial
		   args[2] = time_stamp
		*/
		$all_rewards = CPoker::GetRewards();
		$params = json_decode($_GET['args'], TRUE);
		CPoker::ShowReward("reward" . $params[0], $params[1], $all_rewards);
		$resp["messages"][] = array("type" => "noop", "body" => NULL);
	}

	if ($current_user && $current_user->uid != 0) {
		switch($message_type)
		{
		case "os_poker_sit_down":
			CPoker::CheckRewards("sit", $current_user->uid, json_decode($_GET["players"], TRUE));
			$resp["messages"][] = array("type" => "noop", "body" => NULL);
			break;

		case "os_poker_daily_gift":
			if ($current_user->DailyGift()) {
				$resp["messages"][] = array(
					'type' => 'os_poker_gift_sent',
					'body' => array(
						'text' => t('You have sent !amount free chips to !count buddies', array(
										'!count' => count($current_user->Buddies()),
										'!amount' => 100
										)),
						)
					);
			}
			else {
				$resp["messages"][] = array("type" => "noop", "body" => NULL);
			}				
			break;

		case "os_poker_load_messagebox":
			CScheduler::instance()->Trigger("inbox");
			$mbox = CScheduler::instance()->GetUnreadTasks("inbox");
			$mboxsize = count($mbox);
			if ($_GET["msgcount"] != $mboxsize)
			{
				$resp["messages"][] = array("type" => "os_poker_messagebox", "body" => array("inbox" => $mboxsize,
																							 "picture" => $base_path . drupal_get_path('module', 'os_poker') . "/images/mailbox.png"));
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
					$notification = array(
						'text' => t("You just challenged !user", array(
										"!user" => $target_user->profile_nickname ? $target_user->profile_nickname : variable_get('anonymous', t('Anonymous')),
										)),
						'title' => t('Challenge'),
						);
					CMessageSpool::instance()->SendInstantMessage($notification);
					$resp["messages"][] = array("type" => "os_poker_notify", "body" => $notification);
				}
			}
			break;

		case "os_poker_activate_item":
			if (isset($_GET["id_item"]) && is_numeric($_GET["id_item"])) {
				$current_user->ActivateItem($_GET["id_item"]);
			}
			else {
				trigger_error(t('Invalid item ID: %item_id', array('%item_id' => isset($_GET["id_item"]) ? $_GET["id_item"] : 'undefined')), E_USER_ERROR);
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
						$notification = array(
							'text' => t("You just invited !user to play", array(
											"!user" => $target_user->profile_nickname ? $target_user->profile_nickname : variable_get('anonymous', t('Anonymous')),
											)),
							'title' => t('Invitation'),
							);
						CMessageSpool::instance()->SendInstantMessage($notification);
						$resp["messages"][] = array("type" => "os_poker_notify", "body" => $notification);
					}
				}
			}
			break;
		case 'os_poker_trigger_error':
			trigger_error("Message triggered error.", E_USER_ERROR);
			break;
		case 'os_poker_trigger_exception':
			throw new Exception('Message triggered exception.');
			break;
		default:
			$resp["messages"][] = array("type" => "noop", "body" => NULL);
			break;
		}
	}
  if(in_array(drupal_strtoupper($message_type), $poker_server_events)) {
		if(preg_match('/\[([^,]+),([^,]+),([^,]+)\]/', $_GET['args'], $args)) {
			array_shift($args);
		}
		//Event setn from the poker server
		//TODO Authenticate the request... how ?
		switch($message_type) {
		case  'BUY_IN':
			list($uid, $game_id, $amount)  = $args;
			if(user_load($uid)) {
				CScheduler::instance()->RegisterTask(new CUpdateUserChipsCount(), $uid, array('live'));
				$resp["messages"][] = array("type" => "debug", "body" => t('Recevied BUY_IN for user !uid', array('!uid' => $uid)));
			}
			else {
				trigger_error(t('Invalid user ID: %uid', array('%uid' => $uid)), E_USER_ERROR);
			}
			break;

		case 'TOURNEY_START':
			list($tourney_serial, $dummy1, $dummy2) = $args;
			$tourney_users = CPoker::TourneyRegisteredUsers($tourney_serial);

			foreach ($tourney_users as $tourney_user) {
				if (user_load($tourney_user->user_serial)) {
						
						CScheduler::instance()->RegisterTask(new CTourneyNotificationMessage, $tourney_user->user_serial, array('live'), "-1 day",
								array($tourney_serial, $tourney_user->description_short, $tourney_user->table_serial));
						$resp["messages"][] = array("type" => "debug", "body" => t('Recevied tourney start notification for user !uid', array('!uid' => $tourney_user->user_serial)));
				}
			}
			break;

		case 'HAND':
		case 'TOURNEY':
		case 'REFILL':
		case 'PRIZE':
		case 'REGISTER':
		case 'UNREGISTER':
		case 'LEAVE':
		case 'SEAT':
		case 'NONE':
			$resp["messages"][] = array("type" => "noop", "body" => NULL);
			break;
		}

	}
	else if (!$resp["messages"]){
		$resp["messages"][] = array("type" => "noop", "body" => NULL);
	}

	_os_poker_process_message_set_header();
	print json_encode($resp);
}


?>
