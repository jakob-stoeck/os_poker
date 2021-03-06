<?php
// $Id: password_policy_password_tab.pages.inc,v 1.2 2008/10/29 14:03:57 miglius Exp $

/**
 * @file
 * The password policy password tab page callbacks.
 */

/**
 * Password change form.
 */
function password_policy_password_tab(&$form_state, $account) {
  $form['account']['pass'] = array(
    '#type' => 'password_confirm',
    '#description' => t('To change the current user password, enter the new password in both fields.'),
    '#size' => 25,
  );
  $form['#uid'] = $account->uid;
  $form['_account'] = array('#type' => 'value', '#value' => $account);
  $form['submit'] = array('#type' => 'submit', '#value' => t('Change'));
  $form['#validate'] = array('password_policy_password_tab_validate');
  $form['#submit'] = array('password_policy_password_tab_submit');
  password_policy_form_alter($form, array(), 'user_profile_form');
  return $form;
}

/**
 * Password change form validation.
 */
function password_policy_password_tab_validate($form, &$form_state) {
  $values = $form_state['values'];

  $pass = trim($values['pass']);
  if (empty($pass)) {
    form_set_error('pass', t('Your password cannot be empty.'));
  }
}

/**
 * Password change form submit.
 */
function password_policy_password_tab_submit($form, &$form_state) {
  $account = $form['_account']['#value'];
  user_module_invoke('submit', $form_state['values'], $account, 'account');
  user_save($account, array('pass' => $form_state['values']['pass']));
  drupal_set_message(t('Password has been changed.'));
}

