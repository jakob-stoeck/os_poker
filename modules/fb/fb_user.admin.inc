<?php
// $Id: fb_user.admin.inc,v 1.3 2010/08/14 04:43:35 yogadex Exp $
/**
 * @file
 * Admin pages and forms for user settings.
 * 
 */

// TODO: add pages to view data in the fb_user_app table.

/**
 * Form builder; Configure settings for this site.
 *
 * @ingroup forms
 * @see system_settings_form()
 */
function fb_user_admin_settings() {
  $options = array(0 => t('<none>')) +
    fb_admin_get_app_options(FALSE);
  if (count($options) == 1) {
    $message = t('You must create an app first!');
    drupal_set_message($message, 'error');
    return array('help' => array('#value' => $message));
  }

  $form[FB_USER_VAR_STATS] = array(
    '#type' => 'checkbox',
    '#title' => t('Record Statistics'),
    '#description' => t('Maintain data about the most recent visit of each user who has authorized an app.  Enable if you want this sort of record kept.  Enable also if you want to run cron actions for each user of an app.  Disable for better performance.'),
    '#default_value' => variable_get(FB_USER_VAR_STATS, TRUE),
  );

  $form['fb_user_alter'] = array(
    '#type' => 'fieldset',
    '#title' => t('Form alters'),
    '#description' => t('Add connect button to user forms.  If user has connected, show the user\'s name and profile picture.'),
  );
  $form['fb_user_alter'][FB_USER_VAR_ALTER_REGISTER] = array(
    '#type' => 'checkbox',
    '#title' => t('Registration form'),
    '#default_value' => variable_get(FB_USER_VAR_ALTER_REGISTER, TRUE),
  );
  $form['fb_user_alter'][FB_USER_VAR_ALTER_LOGIN] = array(
    '#type' => 'checkbox',
    '#title' => t('Login form'),
    '#default_value' => variable_get(FB_USER_VAR_ALTER_LOGIN, TRUE),
  );
  
  return system_settings_form($form);
}

/**
 * Not truly hook_form_alter(), this is called from fb_user_form_alter().
 */
function fb_user_admin_form_alter(&$form, &$form_state, $form_id) {
  // Add our settings to the fb_app edit form.
  if (isset($form['fb_app_data'])) {
    $fb_app = $form['#fb_app'];
    $fb_user_data = _fb_user_get_config($fb_app);
    
    $form['fb_app_data']['fb_user'] = array(
      '#type' => 'fieldset',
      '#title' => t('Facebook user settings'),
      '#tree' => TRUE,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
        
    $form['fb_app_data']['fb_user']['create_account'] = array(
      '#type' => 'radios',
      '#title' => t('Create Local Account'),
      '#description' => t('This option will create a local account and an entry in the authmap table when a user authorizes a canvas page or connects using Facebook Connect.  If not, Drupal\'s built in user registration will still work.'),
      '#options' => array(
        FB_USER_OPTION_CREATE_NEVER => t('Do not create accounts automatically'),
        FB_USER_OPTION_CREATE_LOGIN => t('If user has authorized the app'),
      ),
      '#default_value' => $fb_user_data['create_account'],
      '#required' => TRUE,
    );

    $default = $fb_user_data['map_account'];
    if (!is_array($default)) { // This check is for backward compatibility.  Should be removed eventually.
      $default = array($default);
    }
    $form['fb_app_data']['fb_user']['map_account'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Map Accounts'),
      '#description' => t('Mapping an account means creating an entry in the authmap table.  This allows Drupal to know which Facebook id corresponds to which local uid. <br/>Matching based on email works when the email extended permission is requested and only if the user is not already mapped to another account.'),
      '#options' => array(
        //FB_USER_OPTION_MAP_NEVER => t('Never map accounts'),
        FB_USER_OPTION_MAP_ALWAYS => t('Map account when both local uid and Facebook id are known'),
        FB_USER_OPTION_MAP_EMAIL => t('Map account when Facebook email exactly matches local account'),
      ),
      '#default_value' => $default,
    );
    
    // Choose a role to be granted to anyone who authorizes the app.
    $form['fb_app_data']['fb_user']['new_user_rid'] = array(
      '#type' => 'select',
      '#title' => t('App user role'),
      '#options' => user_roles(1),
      '#description' => t('When a local user has authorized the app, the user will be granted this role.'),
      '#default_value' => $fb_user_data['new_user_rid'],
    );
  }

}