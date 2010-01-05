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

/*
**
*/

function	os_poker_sign_up_form_validate($form, &$form_state)
{
	$form_state['values']["name"] = $form_state['values']["username"] = $form_state['values']["mail"];
	
	password_policy_password_validate($form, $form_state);
	user_register_validate($form, $form_state);

}

function	os_poker_sign_up_form_submit($form, &$form_state)
{
	password_policy_password_submit($form, $form_state);
	user_register_submit($form, $form_state);

}

function	os_poker_sign_up_form($form_state)
{
	require_once(drupal_get_path('module', 'password_policy') . "/password_policy.module");
	
	$form = array();
				
	$form["name"] = array(
							'#type' => 'hidden',
					);
					
	$form["mail"] = array(
							'#type' => 'textfield',
							'#title' => t("Your Email"),
							'#attributes' => array("class" => "custom_input"),
							'#required' => TRUE,
					);

	$form["pass"] = array(
							'#type' => 'password',
							'#attributes' => array("class" => "custom_input"),
							'#title' => t("New Password"),
							'#required' => TRUE,
					);
					
	$form['submit'] =	array(
								'#type' => 'submit',
								'#value' => t('Send'),
								'#attributes' => array("style" => "display:none;"),
						);
	
	$form['f_submit'] = 	array(
								'#type' => 'markup',
								'#value' => '<div onclick="javascript:os_poker_submit(this, \'os-poker-sign-up-form\');" ' .
											" class='poker_submit'" .
											" ><div class='pre'>&nbsp;</div><div class='label'>" . t("Sign Up") . "</div><div class='user_login_clear'></div></div>",
							);
										
	$form['#redirect'] = array("poker/first_profile");
	
	
	$uid = isset($form['#uid']) ? $form['#uid'] : NULL;

	$policy = _password_policy_load_active_policy();
  
	$translate = array();
	if (!empty($policy['policy']))
	{
		// Some policy constraints are active.
		password_policy_add_policy_js($policy, $uid);
		foreach ($policy['policy'] as $key => $value)
		{
			$translate['constraint_'. $key] = _password_policy_constraint_error($key, $value);
		}
	}

	return $form;
}

/*
**
*/

function	os_poker_profile_personal_settings_form_submit($form, &$form_state)
{
	$cuser = CUserManager::instance()->CurrentUser();
	
	$profile_accept_gifts = (isset($form_state["values"]['profile_options']["profile_accept_gifts"]) && $form_state["values"]['profile_options']["profile_accept_gifts"] != FALSE);
	$profile_ignore_buddy = (isset($form_state["values"]['profile_options']["profile_ignore_buddy"]) && $form_state["values"]['profile_options']["profile_ignore_buddy"] != FALSE);
	$profile_email_notify = (isset($form_state["values"]['profile_options']["profile_email_notify"]) && $form_state["values"]['profile_options']["profile_email_notify"] != FALSE);
	$profile_newsletter = (isset($form_state["values"]['profile_options']["profile_newsletter"]) && $form_state["values"]['profile_options']["profile_newsletter"] != FALSE);
	$profile_html_email = (isset($form_state["values"]['profile_options']["profile_html_email"]) && $form_state["values"]['profile_options']["profile_html_email"] != FALSE);
	
	if ($cuser->profile_accept_gifts != $profile_accept_gifts)
	{
		$cuser->profile_accept_gifts = $profile_accept_gifts;
	}	
	if ($cuser->profile_ignore_buddy != $profile_ignore_buddy)
	{
		$cuser->profile_ignore_buddy = $profile_ignore_buddy;
	}	
	if ($cuser->profile_email_notify != $profile_email_notify)
	{
		$cuser->profile_email_notify = $profile_email_notify;
	}	
	if ($cuser->profile_newsletter != $profile_newsletter)
	{
		$cuser->profile_newsletter = $profile_newsletter;
	}
	if ($cuser->profile_html_email != $profile_html_email)
	{
		$cuser->profile_html_email = $profile_html_email;
	}
	
	$cuser->Save();
}

function	os_poker_profile_personal_settings_form($form_state)
{
	$form = array();
	
	$cuser = CUserManager::instance()->CurrentUser();
	
	$defaults = array();
	
	if ($cuser->profile_accept_gifts)
		$defaults[] = "profile_accept_gifts";	
	if ($cuser->profile_ignore_buddy)
		$defaults[] = "profile_ignore_buddy";	
	if ($cuser->profile_email_notify)
		$defaults[] = "profile_email_notify";	
	if ($cuser->profile_newsletter)
		$defaults[] = "profile_newsletter";	
	if ($cuser->profile_html_email)
		$defaults[] = "profile_html_email";
	
	$form['profile_options'] = array(
										'#type' => 'checkboxes',
										'#default_value' => $defaults,
										'#options' => array(
															'profile_ignore_buddy' => t('Ignore all new buddy request'),
															'profile_email_notify' => t('Please notify me by email whenever I receive a message'),
															'profile_accept_gifts' => t('I don\'t accept any gift'),
															'profile_newsletter' => t('I wish to receive the weekly email newsletter (recommended)'),
															'profile_html_email' => t('I wish to receive emails in HTML-format'),
														),
									);
							
	$form['submit'] =	array(
								'#type' => 'submit',
								'#value' => t('Send'),
								'#attributes' => array("style" => "display:none;"),
						);
	
	$form['f_submit'] = 	array(
								'#type' => 'markup',
								'#value' => '<div onclick="javascript:os_poker_submit(this, \'os-poker-profile-personal-settings-form\');" ' .
											" class='poker_submit'" .
											" ><div class='pre'>&nbsp;</div><div class='label'>" . t("Save") . "</div><div class='user_login_clear'></div></div>",
							);

	return $form;
}



function	os_poker_profile_email_settings_form($form_state)
{
	$form = array();
	
	$cuser = CUserManager::instance()->CurrentUser();
	
	$form['email'] = array(
							'#type' => 'textfield',
							'#title' => t("Your Email"),
							'#value' => $cuser->mail,
							'#disabled' => TRUE,
					);	
					
	$form['new_email'] = array(
							'#type' => 'textfield',
							'#title' => t("New Email"),
							'#required' => TRUE,
					);
					
	$form['submit'] =	array(
							'#type' => 'submit',
							'#value' => t('Send'),
							'#attributes' => array("style" => "display:none;"),
					);
	
	
	$form['f_submit'] = 	array(
								'#type' => 'markup',
								'#value' => '<div onclick="javascript:os_poker_submit(this, \'os-poker-profile-email-settings-form\');" ' .
											" class='poker_submit'" .
											" ><div class='pre'>&nbsp;</div><div class='label'>" . t("OK") . "</div><div class='user_login_clear'></div></div>",
							);

	return $form;
}

function	os_poker_profile_password_settings_form_validate($form, &$form_state)
{
	password_policy_password_validate($form, $form_state);
}

function	os_poker_profile_password_settings_form_submit($form, &$form_state)
{
	$cuser = CUserManager::instance()->CurrentUser();

	password_policy_password_submit($form, $form_state);
	
	$cuser->pass = $form_state["values"]["pass"];
	
	$cuser->Save();
	

	
	drupal_set_message(t("Password successfully modified"));
}

function	os_poker_profile_password_settings_form($form_state)
{
	$form = array();
	
	$cuser = CUserManager::instance()->CurrentUser();
	
	$form["pass"] = array(
							'#type' => 'password_confirm',
							'#required' => TRUE,
					);
	
	$form['submit'] =	array(
							'#type' => 'submit',
							'#value' => t('Send'),
							'#attributes' => array("style" => "display:none;"),
					);
					
	$form['_account'] =	array(
							'#type' => 'hidden',
							'#value' => $cuser->uid,
					);

	
	$form['f_submit'] = 	array(
								'#type' => 'markup',
								'#value' => '<div onclick="javascript:os_poker_submit(this, \'os-poker-profile-password-settings-form\');" ' .
											" class='poker_submit'" .
											" ><div class='pre'>&nbsp;</div><div class='label'>" . t("OK") . "</div><div class='user_login_clear'></div></div>",
							);
							
	
	$uid = isset($form['#uid']) ? $form['#uid'] : NULL;

	$policy = _password_policy_load_active_policy();
  
	$translate = array();
	if (!empty($policy['policy']))
	{
		// Some policy constraints are active.
		password_policy_add_policy_js($policy, $uid);
		foreach ($policy['policy'] as $key => $value)
		{
			$translate['constraint_'. $key] = _password_policy_constraint_error($key, $value);
		}
	}

	return $form;
}

/*
**
*/

function	os_poker_forgot_password_form_validate($form, &$form_state)
{
	user_pass_validate($form, $form_state);
}

function	os_poker_forgot_password_form_submit($form, &$form_state)
{
	user_pass_submit($form, $form_state);
	$form_state['redirect'] = 'poker/closebox';
}

function	os_poker_forgot_password_form($form_state)
{
	$form['name'] = array(
						'#type' => 'textfield',
						'#title' => t('E-mail'),
						'#attributes' => array("class" => "custom_input"),
						'#maxlength' => max(USERNAME_MAX_LENGTH, EMAIL_MAX_LENGTH),
						'#required' => TRUE,
					);
	
	$form['submit'] = array(
							'#type' => 'submit',
							'#value' => t('Send'),
							'#attributes' => array("style" => "display:none;"),
						);
						
	$form['f_submit'] = 	array(
								'#type' => 'markup',
								'#value' => '<div onclick="javascript:os_poker_submit(this, \'os-poker-forgot-password-form\', null, true);" ' .
											" class='poker_submit'" .
											" ><div class='pre'>&nbsp;</div><div class='label'>" . t("Send") . "</div><div class='user_login_clear'></div></div>",
							);
							
	return $form;
}

/*
**
*/

//Atered version of user.pages.inc : user_pass_reset 

function os_poker_pass_reset(&$form_state, $uid, $timestamp, $hashed_pass, $action = NULL) {
  global $user;
  
  // Check if the user is already logged in. The back button is often the culprit here.
  if ($user->uid)
  {
    drupal_set_message(t('You have already used this one-time login link. It is not necessary to use this link to login anymore. You are already logged in.'));
    drupal_goto();
  }
  else
  {
    // Time out, in seconds, until login URL expires. 24 hours = 86400 seconds.
    $timeout = 86400;
    $current = time();
    // Some redundant checks for extra security ?
    if ($timestamp < $current && $account = user_load(array('uid' => $uid, 'status' => 1)) ) {
      // Deny one-time login to blocked accounts.
      if (drupal_is_denied('user', $account->name) || drupal_is_denied('mail', $account->mail)) {
        drupal_set_message(t('You have tried to use a one-time login for an account which has been blocked.'), 'error');
        drupal_goto();
      }

      // No time out for first time login.
      if ($account->login && $current - $timestamp > $timeout) {
        drupal_set_message(t('You have tried to use a one-time login link that has expired. Please request a new one using the form below.'));
        drupal_goto('poker/forgot-password');
      }
      else if ($account->uid && $timestamp > $account->login && $timestamp < $current && $hashed_pass == user_pass_rehash($account->pass, $timestamp, $account->login)) {
        // First stage is a confirmation form, then login
        if ($action == 'login') {
          watchdog('user', 'User %name used one-time login link at time %timestamp.', array('%name' => $account->name, '%timestamp' => $timestamp));
          // Set the new user.
          $user = $account;
          // user_authenticate_finalize() also updates the login timestamp of the
          // user, which invalidates further use of the one-time login link.
          user_authenticate_finalize($form_state['values']);
          drupal_set_message(t('You have just used your one-time login link. It is no longer necessary to use this link to login. Please change your password.'));
					//          drupal_goto('poker/profile/settings/'. $user->uid);
          drupal_goto('<front>');
        }
        else {
          $form['message'] = array('#value' => t('<p>This is a one-time login for %user_name and will expire on %expiration_date.</p><p>Click on this button to login to the site and change your password.</p>', array('%user_name' => $account->name, '%expiration_date' => format_date($timestamp + $timeout))));
          $form['help'] = array('#value' => '<p>'. t('This login can be used only once.') .'</p>');
          $form['submit'] = array('#type' => 'submit', '#value' => t('Log in'));
          $form['#action'] = url("user/reset/$uid/$timestamp/$hashed_pass/login");
          return $form;
        }
      }
      else {
        drupal_set_message(t('You have tried to use a one-time login link which has either been used or is no longer valid. Please request a new one using the form below.'));
        drupal_goto('poker/forgot-password');
      }
    }
    else {
      // Deny access, no more clues.
      // Everything will be in the watchdog's URL for the administrator to check.
      drupal_access_denied();
    }
  }
}

/*
**
*/

function	os_poker_buddy_search_form($form_state)
{
	// Access log settings:
	$form['online_only'] = 	array(
														'#type' => 'checkbox',
														'#title' => t('Search Online player only!'),
														'#default_value' => variable_get('online_only', 0),
												);
												
	$form['profile_nickname'] =	array(
															'#type' => 'textfield',
															'#title' => t('Nickname'),
															'#size' => 30,
															'#maxlength' => 64,
													);
													
	$form['mail'] =	array(
												'#type' => 'textfield',
												'#title' => t('E-mail'),
												'#size' => 30,
												'#maxlength' => 64,
										);
										
	$sex_options =	array(
							NULL => "--",
							"Male" => t("Male"),
							"Female" => t("Female"),
					);
	
	$form['profile_gender'] =	array(
														'#type' => 'select',
														'#title' => t('Gender'),
														'#options' => $sex_options,
												);
	
	$level_options =	array(
								t("Level1"),
								t("Level2"),
						);
	
	$form['level'] =	array(
												'#type' => 'select',
												'#title' => t('Level'),
												'#options' => $level_options,
										);
	
	$form['profile_city'] =	array(
														'#type' => 'textfield',
														'#title' => t('City'),
														'#size' => 30,
														'#maxlength' => 64,
												);
	
	$form['profile_country'] =	array(
															'#type' => 'textfield',
															'#title' => t('Country'),
															'#size' => 30,
															'#maxlength' => 64,
													);
						
	$form['submit'] =	array(
								'#type' => 'submit',
								'#value' => t('Send'),
								'#attributes' => array("style" => "display:none;"),
						);
	
	$form['f_submit'] = array(
								'#type' => 'markup',
								'#value' => '<div class="clear"></div><div onclick="javascript:os_poker_submit(this, \'os-poker-buddy-search-form\');" ' .
											" class='poker_submit'" .
											" ><div class='pre'>&nbsp;</div><div class='label'>" . t("Search") . "</div></div>",
							);

	return $form;
}
/*
function os_poker_buddy_search_form_submit($form, &$form_state)
{
  os_poker_buddies_page("search");
}
*/

/*
**
*/

function	os_poker_first_profile_form($form_state)
{
	$form = array();
	$pfields = array();
							
	$sql = "SELECT `name`, `options` FROM `{profile_fields}` WHERE `category` LIKE '%s'";
	
	$res = db_query($sql, PROFILE_CATEGORY);
	
	while (($obj = db_fetch_object($res)))
	{
		$formatv = array();
		
        $lines = split("[\n\r]", $obj->options);
        foreach ($lines as $line)
		{
			if ($line = trim($line))
			{
				$formatv[$line] = t($line);
			}
		}
		
		$pfields[$obj->name] = $formatv;
	}
	
	$cuser = CUserManager::instance()->CurrentUser();
	$nick = $cuser->profile_nickname;
	
	$form["profile_nickname"] = array(
										'#type' => 'textfield',
										'#title' => t('Nickname'),
										'#attributes' => array("class" => "custom_input"),
										'#default_value' => $cuser->profile_nickname,
								);
								
	/*if (!empty($nick) && $cuser->picture && strlen($cuser->picture) > 0)
	{							
		$form['picture_view'] = array(
											'#type' => 'markup',
											'#value' => "<img src='" . $cuser->picture . "' alt='Picture' style='width:50px;height:50px'/>",
									);
	}*/
								
	$form['picture_upload'] = 	array(
										'#type' => 'file',
										'#title' => t('Profile photo'),
										'#size' => 25,
										'#description' => t('Upload a photo. Max : %dimensions and %size kB.',
															array('%dimensions' => variable_get('user_picture_dimensions', '85x85'),
															'%size' => variable_get('user_picture_file_size', '30'))
															) .' '. variable_get('user_picture_guidelines', ''),
										'#attributes' => array("class" => "custom_input"),
								);
	
	$form['profile_gender'] =	array(
										'#type' => 'radios',
										'#title' => t('Gender'),
										'#options' => $pfields['profile_gender'],
										'#attributes' => array("class" => "custom_radio"),
										'#suffix' => '<div class="clear"></div>',
										'#default_value' => $cuser->profile_gender,
								);
	
	
	
	$form["profile_dob"] =	array(
									'#type' => 'date',
									'#title' => t('Birthday'),
									'#attributes' => array("class" => "custom_input date"),
									'#default_value' => $cuser->profile_dob,
							);

	$form['profile_country'] =	array(
										'#type' => 'select',
										'#title' => t('Country'),
										'#options' => array(NULL => t('Select...')) + $pfields['profile_country'],
										'#attributes' => array("class" => "custom_input"),
										'#default_value' => $cuser->profile_country,
								);

	$form["profile_city"] = array(
										'#type' => 'textfield',
										'#title' => t('City'),
										'#attributes' => array("class" => "custom_input"),
										'#default_value' => $cuser->profile_city,
								);
								
	$form['submit'] =	array(
								'#type' => 'submit',
								'#value' => t('Send'),
								'#attributes' => array("style" => "display:none;"),
						);
						
	if (!empty($nick))
	{							
		$form['picture_view'] = array(
											'#type' => 'markup',
											'#value' => "<div class='separator'>&nbsp;</div>",
									);
	}
	
	$form['f_submit'] = array(
								'#type' => 'markup',
								'#value' => '<div onclick="javascript:os_poker_submit(this, \'os-poker-first-profile-form\');" ' .
											" class='poker_submit'" .
											" ><div class='pre'>&nbsp;</div><div class='label'>" . ((empty($nick)) ? t("Join") : t("Save Profile")) . "</div><div class='user_login_clear'></div></div>",
							);
					
	if (empty($nick))
	{
		$form["skip"] = array(
								'#value' => "<div class=\"skip_form\">" .t("Skip this step") . " " . "<a class=\"yellow\" href=\"javascript:void(0);\" onclick=\"javascript:os_poker_submit(this, 'os-poker-first-profile-form');\">" . t("Here") . "</a></div>",
						);
						
		$form['#redirect'] = '<front>';
		
		$form['first_profile'] = array(
								'#type' => 'hidden',
								'#value' => TRUE,
							);
	}
					
	$form['#attributes'] = array('enctype' => "multipart/form-data");	
	
	return $form;
}

/*
**
*/

function os_poker_first_profile_form_validate($form, &$form_state)
{
	$cuser = CUserManager::instance()->CurrentUser();
	$edit = & $form_state['values'];
	
	$f = array("#uid" => $cuser->uid);
	
	user_validate_picture($f, $form_state);
	
	if (!empty($edit["profile_nickname"]) && _os_poker_nickname_exists($edit["profile_nickname"], $cuser->uid) == TRUE)
	{
		form_set_error('profile_nickname', t('Nickname') . " " . $edit["profile_nickname"] . " " . t("already exists."));
	}

	if (form_get_errors() == NULL)
		{
			$nick = $cuser->profile_nickname;
			if (!empty($nick))
				drupal_set_message(t("Thank you for updating your profile."));
		}
}

/*
**
*/

function os_poker_first_profile_form_submit($form, &$form_state)
{
	require_once(drupal_get_path('module', 'os_poker') . "/scheduler.class.php");

	$cuser = CUserManager::instance()->CurrentUser(TRUE);
	$edit = & $form_state['values'];
	$profileComplete = TRUE;
	
	if (empty($edit["profile_nickname"]))
	{
		$cuser->profile_nickname = _os_poker_rand_player();
		$profileComplete &= FALSE;
	}
	else
	{
		$cuser->profile_nickname = $edit["profile_nickname"];	
	}
	
	if (!empty($edit["profile_dob"])) { $cuser->profile_dob = $edit["profile_dob"]; } else { $profileComplete &= FALSE; }
	if (!empty($edit["profile_gender"])) { $cuser->profile_gender = $edit["profile_gender"]; } else { $profileComplete &= FALSE; }
	if (!empty($edit["profile_city"])) { $cuser->profile_city = $edit["profile_city"]; } else { $profileComplete &= FALSE; }
	if (!empty($edit["profile_country"])) { $cuser->profile_country = $edit["profile_country"]; } else { $profileComplete &= FALSE; }
	if (!empty($edit["picture"])) { $cuser->picture = $edit["picture"]; } else { $profileComplete &= FALSE; }

	/* if picture is always default but gender has been defined, set a sex specific avatar */
	if (empty($edit["picture"]) && ($cuser->picture == $cuser->DefaultValue("picture") || $cuser->picture == drupal_get_path("theme", "poker")."/images/picture_default_male.jpg" || $cuser->picture == drupal_get_path("theme", "poker")."/images/picture_default_female.jpg"))
		{
			if ($cuser->profile_gender == "Male")
				$cuser->picture = drupal_get_path("theme", "poker") . "/images/picture_default_male.jpg";
			else if ($cuser->profile_gender == "Female")
				$cuser->picture = drupal_get_path("theme", "poker") . "/images/picture_default_female.jpg";
		}

	//Check Profile complete
	if ($profileComplete && $cuser->CompleteProfile() == FALSE)
	{
		$nchip = $cuser->Chips();
		$cuser->chips = $nchip +2000;
		$cuser->SetProfileComplete();
		
		if (isset($edit["first_profile"]))
		{
			CScheduler::instance()->RegisterTask(new CDelayMessage(), $cuser->uid, 'login', "-1 Day", array("type" => "os_poker_jump",
																										"body" => array("lightbox" => TRUE,
																														"url" => url("poker/buddies/invite", array("query" => array("height" => 442, "width" => 603), "absolute" => TRUE)))));
		}
	}
	else if (isset($edit["first_profile"]))
	{
		CScheduler::instance()->RegisterTask(new CDelayMessage(), $cuser->uid, 'login', "-1 Day", array("type" => "os_poker_jump",
																									"body" => array("lightbox" => TRUE,
																													"url" => url("poker/profile/update", array("query" => array("height" => 442, "width" => 603), "absolute" => TRUE)))));
																													
																													
	}

	$cuser->Save();
	
	//Trigger the invitation bonus
	CScheduler::instance()->Trigger('first_login');
	CScheduler::instance()->RegisterTask(new CDailyChips(), $cuser->uid, array('login', "live"), "+1 Day");
}

/*
**
*/

function	os_poker_buddies_invite_form_validate($form, &$form_state)
{
  // by ilo: make this invitation submit testable, avoid using javascript to set 'emails'
  // build $form_state['values']['email'] based on the form fields instead of javascript.
  $emails = array();
  for ($cntr = 1; $cntr <=  5; $cntr++) {
    if (!empty($form_state['values']['mail_'. $cntr])) {
      $emails[] = $form_state['values']['name_'. $cntr] ." <". $form_state['values']['mail_'. $cntr] .">";
    }
  }
  $form_state['values']['email'] = implode(',', $emails);


	user_relationship_invites_invite_form_validate($form, $form_state);
	invite_form_validate($form, $form_state);
}

function	os_poker_buddies_invite_form_submit($form, &$form_state)
{
	invite_form_submit($form, $form_state);
}

function	os_poker_buddies_invite_form($form_state)
{
	$form = array();
		
	for ($i = 0; $i < 5; $i++)
	{
		$nb = $i+1;
		$form["name_" . $nb] = array(
									'#type' => 'textfield',
									'#title' => ($i == 0 ? t("Name") : ""),
									'#prefix' => '<div class="clear"></div><div class="num" '.($i == 0 ? "style='margin-top: 14px'" : "").' >'.$nb.'</div>',
							);

		$form["mail_" . $nb] = array(
									'#type' => 'textfield',
									'#title' => ($i == 0 ? t("E-Mail") : ""),
									'#attributes' => array(
															"class" => "invite_target_mail",
															"number" => $nb,
													),
							);
	}
					
	$form["message"] = array(
							'#type' => 'textarea',
							'#title' => "Message",
							'#resizable' => FALSE,
					);
					
	$form["email"] = array(
							'#type' => 'hidden',
							'#default_value' => '',
					);

	$form['submit'] = 	array(
								'#type' => 'submit',
								'#value' => t('Send invite'),
								'#attributes' => array("style" => "display:none;"),
						);
	
	$form['f_submit'] = 	array(
								'#type' => 'markup',
								'#value' => '<div class="clear"></div><div class="TeaseMore"><div onclick="javascript:os_poker_submit(this, \'os-poker-buddies-invite-form\');" ' .
											" class='poker_submit big'" .
											" ><div class='pre'>&nbsp;</div><div class='label'>" . t("Send") . "</div></div></div>",
							);
										
	$cuser = CUserManager::instance()->CurrentUser();
	
	//invite stuff :
	
	$remaining_invites = invite_get_remaining_invites($cuser->DrupalUser());

	if ($remaining_invites == 0)
	{
		  // Deny access when NOT resending an invite.
		  drupal_set_message(t("Sorry, you've reached the maximum number of invitations."), 'error');
		  drupal_goto(referer_uri());
	}
	
	$form['resent'] = array(
		'#type' => 'value',
		'#value' => 0,
	);
	$form['reg_code'] = array(
		'#type' => 'value',
		'#value' => NULL,
	);
	
	if ($remaining_invites != INVITE_UNLIMITED)
	{
		$form['remaining_invites'] = array(
											'#type' => 'value',
											'#value' => $remaining_invites,
									);
	}
  
    // Sender e-mail address.
	if ($user->uid && variable_get('invite_use_users_email', 0)) {
		$from = $user->mail;
	}
	else {
		$from = variable_get('site_mail', ini_get('sendmail_from'));
	}
	// Personalize displayed e-mail address.
	// @see http://drupal.org/project/pmail
	if (module_exists('pmail')) {
		$from = personalize_email($from);
	}
	$form['from'] = array(
							'#type' => 'hidden',
							'#value' => check_plain($from),
					);
					
	$allow_multiple = user_access('send mass invitations');
	
	if (!$allow_multiple)
		drupal_set_message(t("'send mass invitations' permission must be set !"), 'error');
		
	
  
	//user_relationship stuff :
	
	$new_user = drupal_anonymous_user();
	module_load_include('inc', 'user_relationships_ui', 'user_relationships_ui.forms');
	$form += user_relationships_ui_request_form($cuser->uid, $new_user->uid, $form);
	$form['rtid']['#weight'] = 0;
										
	$form['#redirect'] = array("poker/buddies/invitedlist");
	
	return $form;
}


/*
**
*/

include_once("os_poker_admin.inc");

?>
