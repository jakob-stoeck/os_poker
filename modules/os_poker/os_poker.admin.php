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
    'abuse_report' => array(
      '#type' => 'fieldset',
      '#title' => t('Abuse reports'),
      '#description' => t('Settings for abuse reports sent by users'),
      'os_poker_abuse_mail_to' => array(
        '#type' => 'textfield',
        '#title' => t('Receiver address'),
        '#description' => t('The email address abuse reports are sent to.'),
        '#default_value' => isset($form_state['values']['os_poker_abuse_mail_to']) ? $form_state['values']['os_poker_abuse_mail_to'] : $os_poker_abuse_mail_to,
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