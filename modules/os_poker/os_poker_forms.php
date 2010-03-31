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

/**
 * Validator for the custom signup form. Sets expected values in $form_state.
 */
function	os_poker_sign_up_form_validate($form, &$form_state)
{
  if (variable_get('user_email_verification', TRUE)) {
		if (!$form_state['values']["pass"]) {
				form_set_error('pass', t("You must enter a valid password to proceed."));
		} else if ($form_state['values']["pass"] != $form_state['values']["pass2"]) {
				form_set_error('pass2', t("The two passwords do not match."));
		}
	}
		
	$form_state['values']["name"] = $form_state['values']["username"] = $form_state['values']["mail"];
}

/**
 * Final validator for the custom signup form. Filters duplicated error since
 * name is always equals to email (@see os_poker_sign_up_form_validate).
 */
function os_poker_sign_up_form_final_validate($form, &$form_state) {
  $messages = drupal_get_messages('error', TRUE);
  $errors = $messages['error'] ? $messages['error'] : array();
  $replacements = array(
    t('You must enter a username.') => FALSE,
    t('The name %name is already taken.', array('%name' => $form_state['values']["mail"])) => FALSE,
    t('The e-mail address %email is already registered. <a href="@password">Have you forgotten your password?</a>', array('%email' => $form_state['values']['mail'], '@password' => url('user/password')))
      => t('The e-mail address %email is already registered. !password', array('%email' => $form_state['values']['mail'], '!password' => l(
        t('Have you forgotten your password?'),
        "poker/forgot-password", array(
          "attributes" => array(
            "title" => t("Request new password via e-mail") . ".",
            "class" => "thickbox",
          ),
          "query" => array(
            "height" => "165",
            "width" => "382",
            "keepThis" => "true",
            "TB_iframe" => "true",
          ),
    ))))
  );
  foreach($errors as $error) {
    $replacement = $replacements[$error];
    if($replacement !== FALSE) {
      drupal_set_message($replacement ? $replacement : $error, 'error');
    }
  }
}

function	os_poker_sign_up_form($form_state)
{
	require_once(drupal_get_path('module', 'password_policy') . "/password_policy.module");

	$form = array();
	$account = array(); // not used, but needed by the hook

	/* Invoke the user_relationship_invites_user() hook to ensure buddy relationship will be triggered
		 upon submitting the sign up form */
	if (!empty($_SESSION[INVITE_SESSION])) {
		$form = user_relationship_invites_user('register', $form, $account, NULL);
		// Force buddy request to be approved
		$form['relationship_invite_approve'] = array('#type' => 'value', '#value' => 'approve');
	} else {
		$form = array();
	}


	$form["name"] = array(
							'#type' => 'hidden',
					);

	$form["mail"] = array(
							'#type' => 'textfield',
							'#title' => t("Your Email"),
							'#attributes' => array("class" => "custom_input"),
							'#required' => TRUE,

					);
  if (!variable_get('user_email_verification', TRUE)) {
    $form["pass"] = array(
      '#type' => 'password',
      '#attributes' => array("class" => "custom_input"),
      '#title' => t("New Password"),
      '#required' => TRUE,
    );
  } else {
    $form["pass"] = array(
      '#type' => 'password',
      '#attributes' => array("class" => "custom_input"),
      '#title' => t("Password"),
      '#required' => TRUE,
    );
    $form["pass2"] = array(
      '#type' => 'password',
      '#attributes' => array("class" => "custom_input"),
      '#title' => t("Confirm password"),
      '#required' => TRUE,
    );
	}


	$form['profile_email_notify'] = array(
			'#type' => 'hidden',
			'#value' => 1,
			);

	$form['profile_newsletter'] = array(
			'#type' => 'hidden',
			'#value' => 1,
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

  if (!variable_get('user_email_verification', TRUE)) {
    $form['#redirect'] = array("poker/first_profile");
  }


	$uid = isset($form['#uid']) ? $form['#uid'] : NULL;

  if (!variable_get('user_email_verification', TRUE)) {
    $policy = _password_policy_load_active_policy();
  }
  else {
    $policy = array();
  }

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

  $form['#submit'] = array('user_register_submit', 'os_poker_sign_up_form_submit');
  $form['#validate'] = array('os_poker_sign_up_form_validate', 'user_register_validate', 'os_poker_sign_up_form_final_validate');

	array_unshift($form['#submit'], 'password_policy_password_submit');
	array_unshift($form['#validate'], 'password_policy_password_validate');

	/* Manually trigger the invite_form_alter hook */
  invite_form_alter($form, $form_state, 'user_register');
  if (isset($form['mail']['#default_value'])) {
    $form['mail']['#value'] = $form['mail']['#default_value'];
  }

	/* Mark the signup as invite based to ensure additional chips will be credited */
  if (($code = $_SESSION[INVITE_SESSION]))
  {
    $form['poker_invite'] = array('#type' => 'value', '#value' => TRUE);
  }

  /* A form element to display form error inside the form */
  $form['errors'] = array(
    '#type' => 'markup',
    '#pre_render' => array('os_poker_sign_up_form_errors'),
  );

  return $form;
}

/**
 * Pre_render handler for os_poker_sign_up_form errors element.
 */
function os_poker_sign_up_form_errors($element) {
  if ($messages = form_get_errors()) {
    //Render the form errors (except those for the 'name' field) as an ul inside
    //a div.messages.errors
    unset($messages['name']);
    $output .= "<div class=\"messages error\">\n";
    $output .= " <ul>\n";
    foreach ($messages as $message) {
      $output .= '  <li>'. $message ."</li>\n";
    }
    $output .= " </ul>\n";
    //Remove form erros from status messages (to avoid duplicate messages).
    $status_messages = drupal_get_messages('error', TRUE);
    foreach($status_messages['error'] as $error) {
      if(!in_array($error, $messages)) {
        drupal_set_message($error, 'error');
      }
    }

    $output .= "</div>\n";
    $element['#value'] = $output;
  }
  return $element;
}

/**
 * Submit handler for os_poker_sign_up_form, should be called after
 * password_policy_password_submit and user_register_submit.
 *
 * @see user_register_submit
 */
function	os_poker_sign_up_form_submit($form, &$form_state) {
  $account = $form_state['user'];
  if ($account && $account->status && variable_get('user_email_verification', TRUE) && !user_access('administer users')) {
    //Active account created awaiting email verification and we are not admin.
    //1. Remove standard message set by user_register_submit
    $message = t('Your password and further instructions have been sent to your e-mail address.');
    $notifications = drupal_get_messages('status', TRUE);
    $notifications = $notifications['status'] ? $notifications['status'] : array();
    foreach($notifications as $notification) {
      if($notification != $message) {
        drupal_set_message($notification);
      }
    }
    //2. Set our confirmation message as overlay.
    $overlay[] = t('Welcome,');
    $overlay[] = t('We have just sent a confirmation e-mail to this address: !mail Look into your mailbox, simply click on the link in the e-mail to confirm your address. As a welcome gift, we have reserved !amount poker chips for you!', array(
      '!mail' => '<span class="adress">'. check_plain($account->mail) . '</span>',
      '!amount' => 1000,
    ));
    $overlay[] = t('If the address is not correct, then you can correct your e-mail address here.');
    $overlay = '<p>'. implode('</p><p>', $overlay) . '</p>';
    os_poker_set_overlay($overlay, array('id' => 'registration-successful'));
  }
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


/*
 * WARNING - we are copying the _user_edit_validate() function from user.module to this file, since
 * in the version of drupal we are using, they dont seem to understand the concept of reuse.
 * Ideally this should be done by calling user_module_invoke() but the user_user hook uses arg(1) to
 * retreive the user ID which is very lame, because we dont pass the user ID in the URL.
 *
 * We could directly call _user_edit_validate() but it would break if that function changes in future.
 * So the safest thing is to "borrow" code locally.
 *
 * We should upgrade the drupal version soon, and fix this code.
 */
function os_poker_user_edit_validate($uid, &$edit) {
  $user = user_load(array('uid' => $uid));
  // Validate the username:
  if (user_access('change own username') || user_access('administer users') || !$user->uid) {
    if ($error = user_validate_name($edit['name'])) {
      form_set_error('name', $error);
    }
    else if (db_result(db_query("SELECT COUNT(*) FROM {users} WHERE uid != %d AND LOWER(name) = LOWER('%s')", $uid, $edit['name'])) > 0) {
      form_set_error('name', t('The name %name is already taken.', array('%name' => $edit['name'])));
    }
    else if (drupal_is_denied('user', $edit['name'])) {
      form_set_error('name', t('The name %name has been denied access.', array('%name' => $edit['name'])));
    }
  }

  // Validate the e-mail address:
  if ($error = user_validate_mail($edit['mail'])) {
    form_set_error('mail', $error);
  }
  else if (db_result(db_query("SELECT COUNT(*) FROM {users} WHERE uid != %d AND LOWER(mail) = LOWER('%s')", $uid, $edit['mail'])) > 0) {
			form_set_error('mail', t('The e-mail address %email is already registered.', array('%email' => $edit['mail'])));
  }
  else if (drupal_is_denied('mail', $edit['mail'])) {
    form_set_error('mail', t('The e-mail address %email has been denied access.', array('%email' => $edit['mail'])));
  }
}

function os_poker_profile_email_settings_form_submit($form, &$form_state)
{
	$cuser = CUserManager::instance()->CurrentUser();
	$cuser->mail = $form_state['values']['mail'];
	$cuser->Save();

	drupal_set_message(t("Email successfully modified"));
}

function os_poker_profile_email_settings_form_validate($form, &$form_state)
{
	$cuser = CUserManager::instance()->CurrentUser();
	$edit = &$form_state['values'];
	os_poker_user_edit_validate($cuser->uid, $edit);
}


function	os_poker_profile_email_settings_form($form_state)
{
	$form = array();

	$cuser = CUserManager::instance()->CurrentUser();

	$form['old_email'] = array(
							'#type' => 'textfield',
							'#title' => t("Your Email"),
							'#value' => $cuser->mail,
							'#disabled' => TRUE,
					);
	$form['name'] = array(
							'#type' => 'hidden',
							'#title' => t("Your name"),
							'#value' => $cuser->name,
					);

	$form['mail'] = array(
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

/**
 * Pahe callback replacement for user/reset
 */
function os_poker_pass_reset_page($uid, $timestamp, $hashed_pass, $action = NULL) {
	/*
   * Password reset link is used even during the registration time as the first login link.
   * In this case we should not show password reset tickbox, instead just login to the account
   * and display first profile as per specs.
   * So if we determine that the user has never logged in, we force skipping the reset password form.
	 * Note: We dont need to do any security checks since the checks are still made in os_poker_pass_reset()
   */
	$account = user_load(array('uid' => $uid, 'status' => 1));
	if ($account && !$account->login) {
		$action = 'login'; // force skipping reset password form.
	}

  module_load_include('php', 'os_poker', 'os_poker_forms');
  os_poker_set_overlay('<h1>'.drupal_get_title()."</h1>\n".drupal_get_form('os_poker_pass_reset', $uid, $timestamp, $hashed_pass, $action), array('id' => 'password-reset'));
  drupal_goto('<front>');
}

/*
 * Atered version of user.pages.inc : user_pass_reset
 */
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
          drupal_set_message(t('You have just used your one-time login link. It is no longer necessary to use this link to login. !settings-page.', array(
            '!settings-page' => l('Please change your password' ,'poker/profile/settings', array(
              'attributes' => array(
                'onclick' => "(function(a){var url = a.href; tb_remove();setTimeout(function(){tb_show('',url, false)},201);})(this);return false;",
              ),
              'query' => array(
                'height' => 442,
                'width' => 603,
                'TB_iframe' => 'true'
              ),
            )),
          )));
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
	$level_options = CPoker::GetStatus();
  $level_options[-1] = '--';
	$form['level'] =	array(
      '#type' => 'select',
      '#title' => t('Level'),
      '#options' => $level_options,
      '#default_value' => -1,
  );

	$form['profile_city'] =	array(
														'#type' => 'textfield',
														'#title' => t('City'),
														'#size' => 30,
														'#maxlength' => 64,
												);

	$form['profile_country'] =	array(
															'#type' => 'select',
															'#title' => t('Country'),
                              '#multiple' => false,
                              '#options' => array_map(get_t(), countries_api_get_options_array($first_element = array(NULL => t('--')))),
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
										'#title' => '<span class="gender_container">' . t("Gender") . '</span>',
										'#options' => $pfields['profile_gender'],
										'#attributes' => array("class" => "custom_radio gender_label"),
										'#suffix' => '<div class="clear"></div>',
										'#default_value' => $cuser->profile_gender,
								);



	$form["profile_dob"] =	array(
									'#type' => 'date',
									'#title' => t('Birthday'),
									'#attributes' => array("class" => "custom_input date"),
									'#default_value' => $cuser->profile_dob ? $cuser->profile_dob : array('month' => 1, 'day' => 1, 'year' => 1990),
							);

	$countries_list = array_map(get_t(), countries_api_get_array('iso2', 'printable_name'));
	uasort($countries_list, "strnatcmp");

	$form['profile_country'] =	array(
										'#type' => 'select',
										'#title' => t('Country'),
										'#options' => array_merge(array(NULL => t('--')), $countries_list),



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

	$cuser->name = $cuser->profile_nickname;
	if (variable_get('user_email_verification', TRUE)) {
			$raw_password = $cuser->pass2;
			$cuser->pass = $raw_password;
			$cuser->pass2 = NULL;
	}
					

	if (!empty($edit["profile_dob"])) { $cuser->profile_dob = $edit["profile_dob"]; } else { $profileComplete &= FALSE; }
	if (!empty($edit["profile_gender"])) { $cuser->profile_gender = $edit["profile_gender"]; } else { $profileComplete &= FALSE; }
	if (!empty($edit["profile_city"])) { $cuser->profile_city = $edit["profile_city"]; } else { $profileComplete &= FALSE; }
	if (!empty($edit["profile_country"])) { $cuser->profile_country = $edit["profile_country"]; } else { $profileComplete &= FALSE; }
	if (!empty($edit["picture"])) { $cuser->picture = $edit["picture"]; } else { $profileComplete &= FALSE; }

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

  //Send mail
  if (variable_get('user_email_verification', TRUE)) {
    $account = $cuser->DrupalUser();
    drupal_mail('os_poker', 'profile', $account->mail, user_preferred_language($account), 
								array('account' => $account,
											'raw_password' => $raw_password));
  }
}

/*
**
*/

function	os_poker_buddies_invite_form_validate(&$form, &$form_state)
{
  // by ilo: make this invitation submit testable, avoid using javascript to set 'emails'
  // build $form_state['values']['email'] based on the form fields instead of javascript.
  $emails = array();
  for ($cntr = 1; $cntr <=  5; $cntr++) {
    if(!empty($form_state['values']['mail_'. $cntr])) {
      if (valid_email_address($form_state['values']['mail_'. $cntr])) {
        $emails[] = $form_state['values']['name_'. $cntr] ." <". $form_state['values']['mail_'. $cntr] .">";
      } else {
        form_error($form['mail_'. $cntr], t('The entered e-mail address is invalid. Please correct it.'));
      }
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
