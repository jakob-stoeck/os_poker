<?php
// $Id

/**
 * @file
 * Auto Admin schemas for os_poker module.
 *
 * @tables {poker_item}, {poker_category}, {poker_operation}, {poker_scheduler}, {poker_user_ext}, {polling_users}, {polling_messages}, {tourneys_schedule}
 */

/**
 * Implementation of hook_autoadmin_schema().
 */
function os_poker_autoadmin_schema() {
  // Auto Admin schema for {tourneys_schedule} table
  $autoadmin_schema['tourneys_schedule'] = array(
    'alias' => 'name',
    'inline_filter' => FALSE,
    'path' => 'admin/content/tourneys_schedule',
    'title' => t('Tourneys Schedule'),
    'title_plural' => t('Tourneys Schedules'),
    'fields' => array(
      'serial' => array(
        'autoadmin_type' => 'primary_key',
        'title' => t('Serial'),
        'title_plural' => t('Serials'),
      ),
      'resthost_serial' => array(
        'autoadmin_type' => 'has_one',
        'title' => t('Resthost Serial'),
        'title_plural' => t('Resthost Serials'),
      ),
      'name' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Name'),
        'title_plural' => t('Names'),
      ),
      'description_short' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Description Short'),
        'title_plural' => t('Description Shorts'),
      ),
      'description_long' => array(
        'autoadmin_type' => 'text',
        'title' => t('Description Long'),
        'title_plural' => t('Description Longs'),
      ),
      'players_quota' => array(
        'autoadmin_type' => 'int',
        'title' => t('Players Quota'),
        'title_plural' => t('Players Quotas'),
      ),
      'players_min' => array(
        'autoadmin_type' => 'int',
        'title' => t('Players Min'),
        'title_plural' => t('Players Mins'),
      ),
      'variant' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Variant'),
        'title_plural' => t('Variants'),
      ),
      'betting_structure' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Betting Structure'),
        'title_plural' => t('Betting Structures'),
      ),
      'seats_per_game' => array(
        'autoadmin_type' => 'int',
        'title' => t('Seats Per Game'),
        'title_plural' => t('Seats Per Games'),
      ),
      'player_timeout' => array(
        'autoadmin_type' => 'int',
        'title' => t('Player Timeout'),
        'title_plural' => t('Player Timeouts'),
      ),
      'currency_serial' => array(
        'autoadmin_type' => 'int',
        'title' => t('Currency Serial'),
        'title_plural' => t('Currency Serials'),
      ),
      'prize_currency' => array(
        'autoadmin_type' => 'int',
        'title' => t('Prize Currency'),
        'title_plural' => t('Prize Currencies'),
      ),
      'prize_min' => array(
        'autoadmin_type' => 'int',
        'title' => t('Prize Min'),
        'title_plural' => t('Prize Mins'),
      ),
      'bailor_serial' => array(
        'autoadmin_type' => 'int',
        'title' => t('Bailor Serial'),
        'title_plural' => t('Bailor Serials'),
      ),
      'buy_in' => array(
        'autoadmin_type' => 'int',
        'title' => t('Buy In'),
        'title_plural' => t('Buy Ins'),
      ),
      'rake' => array(
        'autoadmin_type' => 'int',
        'title' => t('Rake'),
        'title_plural' => t('Rakes'),
      ),
      'sit_n_go' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Sit N Go'),
        'title_plural' => t('Sit N Gos'),
      ),
      'breaks_first' => array(
        'autoadmin_type' => 'int',
        'title' => t('Breaks First'),
        'title_plural' => t('Breaks Firsts'),
      ),
      'breaks_interval' => array(
        'autoadmin_type' => 'int',
        'title' => t('Breaks Interval'),
        'title_plural' => t('Breaks Intervals'),
      ),
      'breaks_duration' => array(
        'autoadmin_type' => 'int',
        'title' => t('Breaks Duration'),
        'title_plural' => t('Breaks Durations'),
      ),
      'rebuy_delay' => array(
        'autoadmin_type' => 'int',
        'title' => t('Rebuy Delay'),
        'title_plural' => t('Rebuy Delays'),
      ),
      'add_on' => array(
        'autoadmin_type' => 'int',
        'title' => t('Add On'),
        'title_plural' => t('Add Ons'),
      ),
      'add_on_delay' => array(
        'autoadmin_type' => 'int',
        'title' => t('Add On Delay'),
        'title_plural' => t('Add On Delays'),
      ),
      'start_time' => array(
        'autoadmin_type' => 'int',
        'title' => t('Start Time'),
        'title_plural' => t('Start Times'),
      ),
      'register_time' => array(
        'autoadmin_type' => 'int',
        'title' => t('Register Time'),
        'title_plural' => t('Register Times'),
      ),
      'active' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Active'),
        'title_plural' => t('Actives'),
      ),
      'respawn' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Respawn'),
        'title_plural' => t('Respawns'),
      ),
      'respawn_interval' => array(
        'autoadmin_type' => 'int',
        'title' => t('Respawn Interval'),
        'title_plural' => t('Respawn Intervals'),
      ),
      'currency_serial_from_date_format' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Currency Serial From Date Format'),
        'title_plural' => t('Currency Serial From Date Formats'),
      ),
      'prize_currency_from_date_format' => array(
        'autoadmin_type' => 'varchar',
        'title' => t('Prize Currency From Date Format'),
        'title_plural' => t('Prize Currency From Date Formats'),
      ),
      'satellite_of' => array(
        'autoadmin_type' => 'int',
        'title' => t('Satellite Of'),
        'title_plural' => t('Satellite Ofs'),
      ),
      'via_satellite' => array(
        'autoadmin_type' => 'bool',
        'title' => t('Via Satellite'),
        'title_plural' => t('Via Satellites'),
      ),
      'satellite_player_count' => array(
        'autoadmin_type' => 'int',
        'title' => t('Satellite Player Count'),
        'title_plural' => t('Satellite Player Counts'),
      ),
    ),
  );

  return $autoadmin_schema;
}