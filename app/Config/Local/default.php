<?php
/**
 * @file default.config
 * http://book.cakephp.org/2.0/en/appendices/glossary.html#term-plugin-syntax
 */
$config = array(
  //Environmental Variables.
  'debug' => 0,
  'Config' => array(
    'language' => 'en',
  ),
  /**
   * @internal - LocalConfig is the namespace for variables you want to protect
   * outside of the global config scope, and Cake Internals.
   * This is for production use. If you need to create a development environment,
   * use develop.config to make a development config file.
   */
  'LocalConfig' => array(
    //Location Timezone
    'Location' => array(
      'timezone' => '',
    ),
    //Localized Cache Config.
    'Cache' => array(
      'default_duration' => '+1 day',
      'permanent_duration' => '+999 days',
      'development_duration' => '+1 day',
      'memcache_servers' => array('127.0.0.1:11211'),
      'memcache_default_server' => '127.0.0.1:11211',
      'memcache_persistent' => TRUE,
      'memcache_compress' => FALSE,
      'memcache_probability' => 100,
    ),
    //Database Configuration.
    'DataSource' => array(
      'default' => array(
        'host' => 'localhost',
        'login' => '',
        'password' => '',
        'database' => '',
        'prefix' => '',
      ),
      'test' => array(
        'host' => 'localhost',
        'login' => '',
        'password' => '',
        'database' => '',
        'prefix' => '',
      ),
    ),
  ),
);
//End Config.
