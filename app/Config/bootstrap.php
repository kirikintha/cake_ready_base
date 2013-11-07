<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('PhpReader', 'Configure');

//Local configuration Directory.
$directory = __DIR__.'/Local/';
$name = 'default';
$ext = '.php';
//Look for default file.
$default_settings = $directory.$name.$ext;
//See if we need to throw an error.
try {
  //check if file exists.
  if(!file_exists($default_settings)) {
    //throw exception if email is not valid
    throw new Exception('Could not find any configuration files, please
      make a configuration file under Config/Local/ that matches '.$default_settings);
  }
}
catch (Exception $e) {
  echo $e->getMessage();
  exit();
}

/**
 * Local Config Loader.
 * @internal - Look to default.php or develop.config to manage changes in your
 * local or production environment.
 */

//Look for default or dev config file. Dev file will *always* overtake the default
// file.
$regex = sprintf("(default|develop)\%s", $ext);
//Scan the settings directory.
$folder = new Folder($directory);
$files = $folder->find($regex);
$local_config = array();
if (!empty($files)) {
  foreach ($files as $file) {
      $hash = str_replace($ext, '', $file);
      $local_config[$hash] = array(
        'path' => $directory.$file,
      );
  }
}
//Create a config reader for our files, that follow our syntax.
$reader = new PhpReader($directory);
Configure::config('LocalSettings', $reader);
//Always load the default settings, then merge the additional settings, so we
//  can set a master default, and have total control over the configuration options.
//  Read config files from app/Config/Local/(default|develop)\.config .
foreach ($local_config as $key => $value) {
  Configure::load($key, 'LocalSettings', TRUE);
}

/**
 * If you are on PHP 5.3 uncomment this line and correct your server timezone
 * to fix the date & time related errors.
 */
date_default_timezone_set(Configure::read('LocalConfig.Location.timezone'));

/**
 * Cache Engine Configuration
 */

// Prefix each application on the same server with a different string, to avoid Memcache and APC conflicts.
$default_duration = Configure::read('LocalConfig.Cache.default_duration');
$perm_duration    = Configure::read('LocalConfig.Cache.permanent_duration');
$prefix           = Configure::read('Cache.prefix');
$engine           = Configure::read('Cache.engine');
//If we do not have an engine, or we do not have a tested engine,
//  then we need to not allow the bootstrap to continue.
$allowed_engines = array('File', 'Apc', 'Memcache');
try {
  //check if
  if(!in_array($engine, $allowed_engines)) {
    //throw exception if email is not valid
    throw new Exception('Could not find a valid caching engine, or no caching
      engine found. Please enable at least one caching engine');
  }
}
catch (Exception $e) {
  echo $e->getMessage();
  exit();
}

//Look for development specs, invalidates the cache very quickly
if (Configure::read('debug') > 0) {
  $perm_duration    =
	$default_duration = Configure::read('LocalConfig.Cache.development_duration');
}

/**
 * Configure the cache used for general framework caching.  Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
  'engine' => 'File',
  'prefix' => $prefix . 'cake_core_',
  'path' => CACHE . 'cores' . DS,
  'serialize' => TRUE,
  'duration' => $default_duration,
  'mask' => 0666,
));

/**
  * Configure the cache for model and datasource caches.  This cache configuration
  * is used to store schema descriptions, and table listings in connections.
  */
Cache::config('_cake_model_', array(
  'engine' => 'File',
  'prefix' => $prefix . 'cake_model_',
  'path' => CACHE . 'models' . DS,
  'serialize' => TRUE,
  'duration' => $default_duration,
  'mask' => 0666,
));

/**
 * Specific app caches here
 */
if ($engine == 'Memcache') {
  //Look for config.
  $servers     = Configure::read('LocalConfig.Cache.memcache_servers');
  $probability = Configure::read('LocalConfig.Cache.memcache_probability');
  $persistent  = Configure::read('LocalConfig.Cache.memcache_persistent');
  $compress    = Configure::read('LocalConfig.Cache.memcache_compress');

	// if the config says memcache, that's what we're using
	Cache::config('default', array( // default - not sure this is used
		'engine' => 'Memcache',
		'servers' => $servers,
		'probability'=> $probability,
		'persistent' => $persistent,
		'compress' => $compress,
		'groups' => array('default'),
		'duration' => $default_duration,
	));

	Cache::config('permanent', array(
		'engine' => 'Memcache',
		'servers' => $servers,
		'probability'=> $probability,
		'persistent' => $persistent,
		'compress' => $compress,
		'groups' => array('permanent'),
		'duration' => $perm_duration,
	));
} else {
	//File config, APC config.
	Cache::config('default', array(
		'engine' => $engine,
		'path' => CACHE . 'default' . DS,
		'serialize' => ($engine === 'File'),
		'groups' => array('default'),
		'duration' => $default_duration,
	));

	Cache::config('permanent', array(
		'engine' => $engine,
		'path' => CACHE . 'permanent' . DS,
		'serialize' => ($engine === 'File'),
		'groups' => array('permanent'),
		'duration' => $perm_duration,
	));
}

/**
 * Load Global Plug-ins.
 */
CakePlugin::loadAll(array(
  array('bootstrap' => true),
));