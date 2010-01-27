<?php

    /**
     * Configuration class.
     */
    class ShindigConfigException extends Exception {}

      $shindigConfig = array(
      "gadget_server" => "http://drupal-404.pokersource.info/drupal6/sites/all/modules/shindig_integrator/shindig/php",
      "web_prefix" => "/drupal6/sites/all/modules/shindig_integrator/shindig/php",
      "default_js_prefix" => "/drupal6/sites/all/modules/shindig_integrator/shindig/php/gadgets/js/",
      "default_iframe_prefix" => "/drupal6/sites/all/modules/shindig_integrator/shindig/php/gadgets/ifr?",
      "include_path" => "/usr/src/drupal6/includes/",
      "settings_php" => "/usr/src/drupal6/sites/default/settings.php",
      "person_service" => "ShindigIntegratorPeopleService",
      "activity_service" => "ShindigIntegratorActivitiesService",
      "app_data_service" => "ShindigIntegratorAppDataService",
      'drupal_base_path' => '/drupal6',
    );
    $GLOBALS["shindigConfig"] = $shindigConfig;

    class ShindigConfig {
    static function get($key)
    {					
      global $shindigConfig;						
     if (isset($shindigConfig[$key])) {
       return $shindigConfig[$key];
     }
     else {
       throw new ShindigConfigException("Invalid Config Key");
     }
    }
  }
