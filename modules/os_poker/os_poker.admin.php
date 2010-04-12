<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function os_poker_admin_form($form_state) {
  $os_poker_abuse_mail_to = variable_get('os_poker_abuse_mail_to', 1);
  if(is_numeric($os_poker_abuse_mail_to)) {
    $os_poker_abuse_mail_to = user_load($os_poker_abuse_mail_to)->mail;
  }
  $form = array(
    'email_addresses' => array(
      '#type' => 'fieldset',
      '#title' => t('Email addresses'),
      '#description' => t('Email addresses used when sending email.'),
      'os_poker_abuse_mail_to' => array(
        '#type' => 'textfield',
        '#title' => t('Abuse reports receiver address'),
        '#description' => t('The email address abuse reports are sent to.'),
        '#default_value' => isset($form_state['values']['os_poker_abuse_mail_to']) ? $form_state['values']['os_poker_abuse_mail_to'] : $os_poker_abuse_mail_to,
      ),
      'os_poker_support_mail' => array(
        '#type' => 'textfield',
        '#title' => t('Support address'),
        '#description' => t("The support email address used in mails' body."),
        '#default_value' => isset($form_state['values']['os_poker_support_mail']) ? $form_state['values']['os_poker_support_mail'] : _os_poker_support_mail(),
      ),
    ),
    'welcome_email' => array(
      '#type' => 'fieldset',
      '#title' => t('Welome Email'),
      '#description' => t('Welcome email sent to user after first profile'),
      'os_poker_mail_welcome_subject' => array(
        '#type' => 'textfield',
        '#title' => t('Email Subject'),
        '#description' => t(''),
        '#default_value' => _os_poker_mail_text('welcome_subject'),
      ),
      'os_poker_mail_welcome_body' => array(
        '#type' => 'textarea',
        '#title' => t('Email Body'),
        '#description' => t(''),
        '#default_value' => _os_poker_mail_text('welcome_body'),
      ),
    ),
    'daily_gift_email' => array(
      '#type' => 'fieldset',
      '#title' => t('Free Chips Email'),
      '#description' => t('Notification email sent when the user receive the Daily Gift from a budy'),
      'os_poker_mail_daily_chips_subject' => array(
        '#type' => 'textfield',
        '#title' => t('Email Subject'),
        '#description' => t(''),
        '#default_value' => _os_poker_mail_text('daily_gift_subject'),
      ),
      'os_poker_mail_daily_chips_body' => array(
        '#type' => 'textarea',
        '#title' => t('Email Body'),
        '#description' => t(''),
        '#default_value' => _os_poker_mail_text('daily_gift_body'),
      ),
    ),
  );
  return system_settings_form($form);
}

function os_poker_admin_form_validate($from, &$form_state) {
  $op = isset($form_state['values']['op']) ? $form_state['values']['op'] : '';
  if($op != t('Reset to defaults')) {
    if (!valid_email_address($form_state['values']['os_poker_abuse_mail_to'])) {
      form_set_error('os_poker_abuse_mail_to', t('The e-mail address you specified is not valid.'));
    }
  }
}
