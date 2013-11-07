<?php
/**
 * AppShell file
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Shell', 'Console');
App::uses('ConnectionManager', 'Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {

  protected static $config_extension = '.php';

  /**
   * @name getOptionParser()
   *  Global options for all custom shells.
   *  --config
   *  -c
   * @return type
   */
  public function getOptionParser() {
    $parser = parent::getOptionParser();
    //Add a config option to the shell.
    $options = array();
     //Configuration options - tell us which config file you want to use
    $options['config'] = array(
      'short' => 'c',
      'help' => 'Select which config file you want to use. "default" or "develop"',
      'default' => 'default',
    );
    $parser->addOptions($options);
    return $parser;
  }

  /**
   * @name startup()
   * @internal - Note, that you should not clear configs here, just read them.
   */
  public function startup() {
    parent::startup();
    if ($this->params['config'] != 'default') {
      //Tell us which configure you are using.
      $name = $this->params['config'] .self::$config_extension;
      $this->out(__('[%s] - Using: %s as your configuration file', date('Y-m-d H:i:s'), $name));
      //Load config.
      $config = $this->_loadConfig($this->params['config']);
      //Change DB Connections.
      @ConnectionManager::drop('default');
      @ConnectionManager::create('default', $config['LocalConfig']['DataSource']['default']);
      @ConnectionManager::getDataSource('default')->connect();
    }
  }

  /**
   * @name _loadConfig ()
   *  Load a local config file. Meant for bootstrap and shell processes.
   * @param type $override
   */
  private function _loadConfig ($override = NULL) {
    $directory = APP.'Config/Local/';
    $name = $override;
    //Look for default file.
    $default_settings = $directory.$name.self::$config_extension;
    //Global check.
    try {
      //check if
      if(!file_exists($default_settings)) {
        //throw exception if email is not valid
        throw new Exception('Could not find any configuration files, please
          make a configuration file under Config/Local/ that matches '.$default_settings);
      }
    }
    catch (Exception $e) {
      $this->_error($e->getMessage());
      exit();
    }
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
    $config = array();
    foreach ($local_config as $key => $value) {
      //We have to get the DB connection out of local settings, so that we can set
      // a new DB connection.
      $setting = $reader->read($key, 'LocalSettings', TRUE);
      $config = Hash::merge($config, $setting);
      //After we do that, we load our local config in.
      Configure::load($key, 'LocalSettings', TRUE);
    }
    return $config;
    //End.
  }

  /**
   * @name _debug();
   *  Debugger.
   * @param type $str
   */
  public function _debug($str) {
    AppController::_debug($str);
  }

  /**
   * @name _out($str)
   *  Prepends the timestamp to the out method.
   * @param type $str
   * @return type
   */
  public function _out($str) {
    $this->out(__('[%s] - %s', date('Y-m-d H:i:s', strtotime('now')), $str));
  }

  //Error
  public function _error($str) {
    $this->error(__('<error>%s</error>', $str));
  }

  //Info
  public function _info($str) {
    $this->out(__('<info>%s</info>', $str));
  }

  //Warning
  public function _warning($str) {
    $this->out(__('<warning>%s</warning>', $str));
  }

  //Comment
  public function _comment($str) {
    $this->out(__('<comment>%s</comment>', $str));
  }

  //Question
  public function _question($str) {
    $this->out(__('<question>%s</question>', $str));
  }

  //End.
}
