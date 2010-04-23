<?php
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
function os_poker_report_abuse_form($form_state, $reported) {
  global $user;
  $form = array(
    /*'text' => array(
      '#type' => 'markup',
      '#value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    ),*/
    'reason' => array(
      '#type' => 'checkboxes',
      '#required' => true,
      '#multiple' => false,
      '#options' => array(
        'image' => t('Player image'),
        'chat' => t('Player chat'),
        'name' => t('Player name'),
        'cheat' => t('Players poker play cheating'),
      ),
    ),
    'details' => array(
      '#type' => 'textarea',
      '#title' => 'Your Message',
      '#description' => '',
      '#resizable' => FALSE,
      '#cols' => 36
    ),
    'submit' => array(
      '#type' => 'submit',
      '#value' => t('Send'),
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
  if($op == t('Send')) {
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
