<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Confugure utility, which is App Wide.
 */
App::uses('CakeLog', 'Log');
App::uses('CakeNumber', 'Utility');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

//Defines the default error type when using the log() function. Used for
//  differentiating error logging and debugging. Currently PHP supports LOG_DEBUG.
define('LOG_ERROR', 2);

//@internal - If you need hard-coded configured variables, do so here.
// Note: That this will be overridden in bootstrap.php if the value is set on the LocalConfig,
// so you can feel safe setting it here. This shouldn't replace *all* configurable values,
// but the configuration options that absolutely must be here.
Configure::write('App.encoding', 'UTF-8');
Configure::write('Routing.prefixes', array('admin'));

//Default Security Options.
Configure::write('Security', array(
  'level' => 'low',
  'salt' => 'a35fd066bc6a3ea05a983d1fa058f6c0764614c8',
  'cipher_seed' => '646666386638373932623961653163',
  //Generate 32 byte key: http://www.csgnetwork.com/wepgeneratorcalc.html
  'key' => 'grJOlgs9kaZGKUTmsBBTjy7MwVjUDvNj',
));

//Default Caching options.
Configure::write('Cache', array(
  //Changing prefixes may be advisable in load balanced environments.
  'prefix' => 'default_',
  'engine' => 'File',
  /**
   * @internal - These options are disabled by default.
   * 'disable' => TRUE,
   * 'check' => TRUE,
   */
));

//Default Session options.
Configure::write('Session', array(
  'defaults' => 'php',
  //You may also want to change prefixes for cookies, if you are in a load balanced
  // environment.
  'cookie' => 'default_',
  'ini' => array(
    //This will allow us to move between secure and insecure sessions.
    //http://www.yodanto.com/development/session-cookies-between-ssl-and-non-ssl-in-cakephp
    'session.cookie_secure' => FALSE
  ),
));

//Error Config.
Configure::write('Error', array(
  'handler' => 'ErrorHandler::handleError',
  'level' => E_ALL ^ E_STRICT ^ E_NOTICE ^ E_DEPRECATED,
  'trace' => true,
));

//Exception Config.
//@todo - create an exception handler that works under ajax.
Configure::write('Exception', array(
  'handler' => 'ErrorHandler::handleException',
  'renderer' => 'ExceptionRenderer',
  'log' => true,
));

/**
 * Configures default file logging options
 */

//Debug Log
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));

//Error Log
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));