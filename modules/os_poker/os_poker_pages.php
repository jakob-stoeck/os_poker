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


require_once(drupal_get_path('module', 'user') . "/user.pages.inc");
require_once(drupal_get_path('module', 'os_poker') . "/os_poker_forms.php");
require_once(drupal_get_path('module', 'simple_payments') . "/gateways/simple_payments_paypal/simple_payments_paypal.module");

/*
**
*/

function	os_poker_first_profile_page()
{
  return theme('os_poker_first_profile', drupal_get_form('os_poker_first_profile_form'), os_poker_random_user_list());
}

/*
**
*/

function	os_poker_buddies_page($action = NULL, $page=0)
{
	$output = "<div id='buddies-tabs'>".theme('buddies_tabs', $action);
	
	switch ($action)
	{
		case "search":
			$lsr = NULL;
		
			if (isset($_REQUEST["form_id"]) && $_REQUEST["form_id"] == "os_poker_buddy_search_form")
			{
				/*$offset = 0;
				$limit = NULL;
				
				if (isset($_REQUEST["page"]))
				{
					$page = $_REQUEST["page"];
					$offset = $page * $limit;
				}*/
					
				$lsr = CUserManager::instance()->SearchUsers($_REQUEST);
			}

			if ($lsr !== NULL)
			{
			  // TODO : remonter la gestion des pages au niveau de CUserManager (fait dans la template temporairement)
			  $output .= theme('buddies_list', CUserManager::instance()->UserList($lsr), CUserManager::instance()->CurrentUser(),  $action, $page);
			}
			else
			{
			  $output .= theme('buddies_search', drupal_get_form('os_poker_buddy_search_form'), CUserManager::instance()->CurrentUser());	
			}
		break;
		case "invite":
			$output .= theme('buddies_invite', drupal_get_form('os_poker_buddies_invite_form'), CUserManager::instance()->CurrentUser());	
			
		break;
		case "invitedlist":
			$cuser = CUserManager::instance()->CurrentUser();
		
			if (isset($_POST["invite_action"]) && !empty($_POST["invite_target"]))
			{
				if ($_POST["invite_action"] == "remind")
				{
					$sql = "SELECT * FROM `{invite}` WHERE `email` = '%s' AND `uid` = %d AND `expiry` < %s LIMIT 1";
			
					$time = (time() * 2);
					$res = db_query($sql, $_POST["invite_target"], $cuser->uid, $time);
					
					if ($res != FALSE)
					{
						$inv = db_fetch_object($res);
						
						if ($inv != FALSE)
						{
							require_once(drupal_get_path('module', 'invite') . "/invite.module");
						
							global $language;
						
							if (!variable_get('invite_use_users_email', 0)) {
								$from = variable_get('invite_manual_from', '');
							}
							else if ($user->uid) {
								$from = $cuser->mail;
							}
							if (!$from) {
								// Never pass an empty string to drupal_mail()
								$from = NULL;
							}
							$invite = _invite_substitutions(array(
																'email' => $_POST["invite_target"],
																'code'  => $inv->reg_code,
																'resent'  => TRUE,
																'data'  => array('subject' => invite_get_subject(), 'message' => NULL),
															));

							// Send e-mail.
							$params = array('invite' => $invite); 
						   $message = drupal_mail('invite', 'invite', $_POST["invite_target"], $language, $params, $from, TRUE);
						   drupal_set_message(t("Invitation resent to !email", array("!email"=> $_POST["invite_target"])));
						}
						else
						{
							drupal_set_message(t("Invitation must have expired to be resent."), 'error');
						}
					}
				
				}
				else if ($_POST["invite_action"] == "delete")
				{
					$sql = "DELETE FROM `{invite}` WHERE `email` = '%s' AND `uid` = %d";
					
					$res = db_query($sql, $_POST["invite_target"], $cuser->uid);
					
					if ($res)
					{
						drupal_set_message(t("Your invite has been deleted."));
					}
				}
			}
		
		  $output .= theme('buddies_invitedlist', $cuser);	
			
		break;
		default :
		  $output .= theme('buddies_list', CUserManager::instance()->CurrentUser()->Buddies(TRUE),  CUserManager::instance()->CurrentUser());
		break;
	}

	return $output."</div>";
}




/*
**
*/

function	os_poker_profile_page($tab, $user_id = NULL, $game_id = NULL)
{
	require_once(drupal_get_path('module', 'os_poker') . "/user.class.php");
	$current_user =  CUserManager::instance()->CurrentUser();
	$target_user = $current_user;
	$external = FALSE;
	
	
	
	if ($user_id != NULL && $user_id != $current_user->uid)
	{
		$target_user =  CUserManager::instance()->User($user_id);;
		$external = TRUE;
	}
	
	switch ($tab)
	{
		case "settings":
			if ($external == FALSE)
			{
				$content = theme('os_poker_profile_settings', 
								drupal_get_form('os_poker_profile_personal_settings_form'),
								drupal_get_form('os_poker_profile_email_settings_form'),
								drupal_get_form('os_poker_profile_password_settings_form')
								);
			}
		break;

		case "update":
			if ($external == FALSE)
			{
				$content = theme('os_poker_profile_update', $target_user, drupal_get_form('os_poker_first_profile_form'));
			}
		break;

		case "rewards":
			if (isset($_GET["list"]) && $_GET["list"] == "rewards" && !empty($_GET["ajax"]))
			{
				return theme('os_poker_reward_fulllist', $target_user->Rewards());
			}
		
			$content = theme('os_poker_rewards', $target_user, $external);
		break;
		
		case "ranking":
		
			$searchParams = array();
			$lsr = CUserManager::instance()->SearchUsers($searchParams);
			$userlist = CUserManager::instance()->UserList($lsr);
			usort($userlist, "_os_poker_sort_buddies");
			$user_rank = array_search($target_user, $userlist);
			
			if (isset($_GET["list"]) && $_GET["list"] == "ranking" && !empty($_GET["ajax"]))
			{
				return theme('os_poker_ranking_list', $userlist);
			}
		
			$content = theme('os_poker_ranking', $target_user, $user_rank + 1, $userlist);
		break;
		
		case "medium":
			$content = theme('os_poker_medium_profile', $target_user, $external, $current_user, $game_id);

      return $content;
		break;
		
		default:
			if (isset($_GET["list"]) && !empty($_GET["ajax"]))
			{
				if ($_GET["list"] == "rewards")
				{
					$content = theme('os_poker_reward_minilist', $target_user);
				}
				else if ($_GET["list"] == "item")
				{
					$content = theme('os_poker_item_minilist', $target_user);
				}
			
				return $content;
			}
			$content = theme('os_poker_profile', $target_user, $external);
		break;
	}
	
	return theme('os_poker_profile_tabs', $tab, $content, $external);
}

/*
**
*/

function	os_poker_messagebox_page()
{
	require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");
	
	$mbox = CScheduler::instance()->GetTasks("inbox");

	if (isset($_GET["list"]) && $_GET["list"] == "messages" && !empty($_GET["ajax"]))
	{
		return theme('os_poker_message_list', $mbox);
	}
	
	// Mark the messages as read - we consider all messages to be read once the user opens the messagebox
	CScheduler::instance()->MarkTasksAsRead();
	// Reset the unread message count in the navbar
	drupal_add_js(drupal_get_path('module', 'os_poker').'/js/os_poker.messageboxreset.js', 'module');
	return theme('os_poker_messagebox', $mbox);
}


/*
**
*/

function	os_poker_messages_popup_page()
{
  return theme('os_poker_messages_popup');
}


/*
**
*/

function	os_poker_forgot_password_page()
{
	require_once(drupal_get_path('module', 'user') . "/user.pages.inc");

	return theme('os_poker_forgot_password', drupal_get_form('os_poker_forgot_password_form'));
}

/*
**
*/
function	os_poker_help_page() {
  drupal_set_title('');
	return theme('os_poker_help');
}

/*
**
*/

function	os_poker_shop_page($tab, $category = NULL, $target_type = NULL, $target_id = NULL, $subtarget_id = NULL)
{
	$content = "";
	if ($tab == NULL || $tab == "shop")
	{	
		require_once(drupal_get_path('module', 'os_poker') . "/user.class.php");
		require_once(drupal_get_path('module', 'os_poker') . "/shop.class.php");
		require_once(drupal_get_path('module', 'os_poker') . "/poker.class.php");

		$subtarget = NULL;
		$buddies = NULL;
		$cats = CShop::ListCategories();
		
		if ($target_type == NULL)
		{
			$target_type = "self";
		}
		
		if ($category == NULL)
		{
			$vcats = array_keys($cats);
			if (count($vcats) > 0)
			{
				$category = $vcats[0];
			}
		}
		
		$prods =  CShop::ListItems($category);
		
		if (isset($_GET["list"]) && $_GET["list"] == "items" && !empty($_GET["ajax"]))
		{
			return 	print theme('os_poker_item_list', $prods);
		}
		
		$current_user =  CUserManager::instance()->CurrentUser();
		$buddies = array_filter($current_user->Buddies(TRUE), "_os_poker_user_accepts_gifts");
		
		switch($target_type)
		{
			case "table":
				$target = array_filter(CPoker::UsersAtTable($target_id, TRUE), "_os_poker_user_accepts_gifts");
				if ($subtarget_id)
					$subtarget = CUserManager::instance()->User($subtarget_id);
					
				$merge = $target + $buddies;
				$special = array();
				foreach($merge as $u)
				{
					$special[$u->uid] = $u->uid;
				}
				$special = array_unique(array_keys($special));
			break;
			case "buddy":
				$target = $buddies;
				$subtarget = CUserManager::instance()->User($target_id);
			break;
			case "self":
				$target = $buddies;
				$subtarget = $current_user;
			break;
		}
			
		if (!empty($_POST["shop_action"]) && !empty($_POST["shop_item"]))
		{
			$action = $_POST["shop_action"];
			$success = TRUE;
			
			switch ($action)
			{
				case "subtarget":
					if ($subtarget->uid == $current_user->uid)
					{
						$success = CShop::BuyItem($_POST["shop_item"], empty($_POST["shop_item_activate"]) ? FALSE : !!$_POST["shop_item_activate"]);
					}
					else
					{
						$success = CShop::GiveItem($_POST["shop_item"], array($subtarget));
					}
				break;
				
				case "target":
					$success = CShop::GiveItem($_POST["shop_item"], $target);
				break;
							
				case "special":
				
					$success = CShop::GiveItem($_POST["shop_item"], $special);
				break;
			}
			
			if ($success == FALSE) {
				$error = theme('poker_error_message', "<h1>" . t("Sorry !") . "</h1>" . t("You don't have enough Chips."));
      } else if ($target_type == 'table') {
        drupal_goto('poker/closebox');
      }
		}
		
		$params = 	array(
							"categories" => $cats,
							"current_category" => $category,
							"items" => $prods,
							"current_user" => $current_user,
							"target_type" => $target_type,
							"target" => $target,
							"subtarget" => $subtarget,
							"target_id" => $target_id,
							"subtarget_id" => $subtarget_id,
							"buddies" => $buddies,
							"special" => $special,
							"error" => $error,
					);
		
		$content = theme('os_poker_shop', $params);
	}
	else if ($tab == "get_chips")
	{
	  $content = theme('os_poker_shop_get_chips', drupal_get_form('chips_paypal_form'), $params);
	}
	
	return theme('os_poker_shop_tabs', $tab, $content);
}



/*
** There are predefined packages of Chips which could be purchased : 50k chips for 1 euro, 100k chips for 2 euros, 1M chips for 10 euros, as defined in a table of chips packages.
*/

function chips_paypal_form($form_state)
{
  $vars = array(
		'module' => 'os_poker',
		'type' => 'chips',
		'custom' => 42,
		'no_shipping' => TRUE,
		'no_note' => TRUE,
		'return' => url('poker/shop/get_chips', array('absolute' => TRUE)),
		'currency_code' => "EUR",
		'undefined_quantity' => 1,
		/*		'quantity' => 1,*/
		);
 
  $form = simple_payments_paypal_payment_form($vars);

  $opts = array("5" => t("75.000 Chips for €5"),
		"10" => t("150.000 Chips for €10"),
		"20" => t("400.000 Chips for €20"),
		"50" => t("1.000.000 Chips for €50"),
		"100" => t("2.500.000 Chips for €100"),
		"150" => t("5.000.000 Chips for €150"));

  $form['amount'] = array(
			  '#type' => 'radios',
			  '#options' => $opts,
			  '#default_value' => "5",
			  /*			  '#description' => t("Please select the amount of chips you want to buy (the first time, open https://sandbox.paypal.com/cgi-bin/webscr in a new tab and initialize the session with paypal sandbox using cmirey@persistant.fr / testtest then come back on this page, click on buy and use poker_1259758311_per@persistant.fr / testtest as sandbox paypal account)"),*/
			  );

  $form['item_name'] = array(
			  '#type' => 'hidden',
			  '#value' => "Chips",
			  );

  $form['#attributes'] = array(
				'target' => "_blank",
				);

  // cmirey : get selected label info
  /*  $form['button'] = array(
			 '#type' => 'button',
			 '#value' => t('Buy chips'),
			 "#attributes" => array("onClick" => "$('#edit-item-name').val($(\"input[name='amount']:checked\").parent().text().substring(0, $(\"input[name='amount']:checked\").parent().text().lastIndexOf('for ')));"),
			 );
*/
  return $form;
}

function chips_paypal_process($payment)
{
}

/*
**
*/


function	os_poker_shop_admin_page()
{

require_once(drupal_get_path('module', 'os_poker') . "/shop.class.php");

$cats = CShop::ListCategories();
$vcats = array_keys($cats);

 $form .= "<table><tr><td>category</td><td>name</td><td>picture</td><td>price</td><td>expiry</td><td>av.</td></tr>";

foreach ($vcats as $catid)
  {
    $items = CShop::ListItems($catid);

	if ($items)
	  {
	    foreach ($items as $item)
	      {	
		$form .= "<tr>".drupal_get_form("os_poker_shop_admin_form_" . $item->id_item, array($item->id_item, $cats, $catid))."</tr>";
	      }
	  }

  }

 $form .= "<tr><td>ADD NEW</td></tr><tr>".drupal_get_form("os_poker_shop_admin_form_" . 0, array(0, $cats, $catid))."</tr>";
 
 $form .= "</table>";
 return $form;
}

?>
