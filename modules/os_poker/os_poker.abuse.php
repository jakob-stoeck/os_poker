<?php
function os_poker_report_abuse_form($form_state, $reported) {
  global $user;
  $form = array(
    'heading' => array(
      '#type' => 'markup',
      '#value' => t('Report abuse by %username.', array('%username' => $reported->profile_nickname)),
    ),
    'reason' => array(
      '#type' => 'checkboxes',
      '#required' => true,
      '#multiple' => false,
      '#title' => t('Reason'),
      '#description' => t('Your reason for reporting %username as abusive.', array('%username' => $reported->profile_nickname)),
      '#options' => array(
        'profile_picture' => t('Offending profile picture'),
        'other' => t('Other'),
      ),
    ),
    'details' => array(
      '#type' => 'textarea',
      '#title' => 'Details',
      '#description' => 'Provide details about the reported abuse.',
    ),
    'submit' => array(
      '#type' => 'submit',
      '#value' => t('Report abuse'),
    ),
    'cancel' => array(
      '#type' => 'submit',
      '#value' => t('Cancel'),
      '#attributes' => array(
        'class' => 'tb_remove',
      ),
    ),
    '#reporter' => $user,
    '#reported' => $reported,
  );
  return $form;
}

function os_poker_report_abuse_form_validate($form, &$form_state) {
  //No custom validation needed.
}

function os_poker_report_abuse_form_submit($form, &$form_state) {
  $op = isset($form_state['values']['op']) ? $form_state['values']['op'] : '';
  if($op == t('Report abuse')) {
    $to = variable_get('os_poker_abuse_mail_to', 1);
    if(is_numeric($to)) {
      $to = user_load($to)->mail;
    }
    $account = $form['#reporter'];
    $from = ($account->profile_nickname ? $account->profile_nickname : $account->name) . '<'. $account->mail .'>';
    $params = array(
      'reason' => $form['reason']['#options'][reset(array_filter($form_state['values']['reason']))],
      'details' => $form_state['values']['details'],
      'reporter' => $form['#reporter'],
      'reported' => $form['#reported'],
    );
    drupal_mail('os_poker', 'abuse', $to, user_preferred_language($account), $params, $from);
    drupal_set_message(t('Your message has been sent.'));
  }
}
?>
