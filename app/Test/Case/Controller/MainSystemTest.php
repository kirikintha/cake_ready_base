<?php
/**
 * @file MainSystemTest.php
 * Main System Test Case
 *  This is *not* to test against data, these are functional
 *
 */
App::uses('AppController', 'Controller');
App::uses('ConnectionManager', 'Model');
App::uses('PhpReader', 'Configure');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class MainSystemTest extends ControllerTestCase {

  /**
   * Fixtures
   *
   * @var array
   */
	public $fixtures = array();

  public $prerequisites = array(
    //Config options.
    'config' => array(
      'directory' => 'Config/Local/',
      'name' => 'default',
      'ext' => '.php',
      'regex' => '(default|develop)\.php',
      //Required config.
      // @todo - allowed values for config test. This is not as easy at it would appear to be.
      'required' => array(
        //Debug level required.
        'debug' => array(0, 1, 2),
        //Language == 'en' right now.
        'Config.language' => array('en'),
        //Local Config is our protected namespace, so we don't ever conflict with
        // Cake's Config internal syntax.
        'LocalConfig.Location.timezone' => array(),
        'LocalConfig.SSL' => array(),
        'LocalConfig.SDK.incomingRedirect' => array(),
        'LocalConfig.SDK.forceSSL' => array(TRUE, FALSE),
        'LocalConfig.DataSource.qns.wsdl' => array(),
        'LocalConfig.DataSource.qns.location' => array(),
        //Caching options required.
        'Cache.prefix' => array(),
        //Must be in allowed list.
        'Cache.engine' => array('File', 'Apc', 'Memcache'),
        //Session is required.
        'Session.defaults' => array(),
        'Session.cookie' => array(),
        //Session session.cookie_secure === FALSE
        // This lets sessions move between http and https.
        'Session.ini' => array(),
        //Security options.
        // Level == low
        'Security.level' => array('low'),
      ),
    ),
    'cache' => array(
      'allowed_engines' => array('File', 'Apc', 'Memcache'),
    ),
    //PHP
    'php' => array(
      'min' => '5.3.20',
      'max' => '5.6.0',
    ),
    //@internal - this is a long list, so you can comment out a lot of these.
    // @todo - remove any un-needed extensions we never need to check.
    'extensions' => array(
      'bcmath',
      'bz2',
//      'calendar',
      'Core',
      'ctype',
      'curl',
      'date',
      'dba',
      'dom',
      'ereg',
//      'exif',
      'fileinfo',
      'filter',
//      'ftp',
//      'gd',
      'gettext',
      'hash',
      'iconv',
      'json',
//      'ldap',
//      'libevent',
      'libxml',
      'mbstring',
      'mcrypt',
      'memcache',
//      'memcached',
//      'mhash',
//      'mongo',
//      'mysql',
//      'mysqli',
//      'mysqlnd',
//      'OAuth',
//      'odbc',
//      'openssl',
      'pcre',
      'PDO',
//      'pdo_mysql',
//      'PDO_ODBC',
//      'pdo_sqlite',
//      'Phar',
//      'posix',
//      'Reflection',
      'session',
//      'shmop',
      'SimpleXML',
//      'snmp',
      'soap',
      'sockets',
      'SPL',
//      'SQLite',
//      'sqlite3',
      'standard',
      'sysvmsg',
      'sysvsem',
      'sysvshm',
      'tokenizer',
//      'uploadprogress',
//      'wddx',
//      'xdebug',
//      'xhprof',
      'xml',
      'xmlreader',
      'xmlrpc',
      'xmlwriter',
      'xsl',
//      'yaml',
//      'zip',
//      'zlib',
    ),
  );

  /**
   * @name testPrerequisites ()
   *  Any Prerequisites we have.
   *  @todo - do we need to test server reqs?
   */
  public function testPrerequisites () {
    //@todo - prerequisites tests.
    //Test that PHP is installed with proper version number.
    // Warning if PHP 5.5 <
    // Error, if under 5.3.20 >=
    $this->_phpVersion();
    //Test that extensions have been installed, and that we can make a list
    // of what we need.
    // @todo - if we need to get version specific, then we can make that happen
    //    here as well?
    $this->_phpExtensions();
  }

  /**
   * @name testFileSystem ()
   *  Test that directories are writeable by the system.
   *  1. Test TMP directories. This is where we will store locations too, in
   *      the file system.
   *  2. @todo - Test Config/Local Directories (should *not* be writable?)
   */
  public function testFileSystem () {
    // app/tmp/*
    $result = is_writable(TMP);
    $expected = true;
    $message = __('%s is not writable, please run "make clean", from the command line in your root directory.', TMP);
    $this->assertEqual($result, $expected, $message);
    //Now, recursively check all subdirectories, to make sure those are all the
    // subdirectories are writable. These *all* need to be writable.
    $folder = new Folder(TMP);
    $items = $folder->tree();
    if (!empty($items[0])) {
      //If we have found folders, check if writable.
      $folders = $items[0];
      foreach ($folders as $path) {
        $result = is_writeable($path);
        $expected = true;
        $message = __('%s is not writable, please run "make clean", from the command line in your root directory.', $path);
        $this->assertEqual($result, $expected, $message);
      }
    }
  }

  /**
   * @name testMainConfig ()
   *  This will check the syntax of the Config being used, after we
   *  have loaded main config files.
   */
  public function testMainConfig () {
    //Make sure config default is there.
    $this->_checkConfig();
    //Test loading config, unloading config.
    $this->_loadConfig();
  }

  /**
   * Private Sub-methods.
   *  These are sub-tasks that cna be built into each test.
   *  DO NOT prefix with test%CamelCasedAction
   *  use: private function _%camelCasedAction
   */

  /**
   * Pre-requisite Tests.
   */

  /**
   * @name _phpVersion ()
   *  PHP version is greater than 5.3.8 and less than 5.4.15
   *  @see http://www.php.net/ for mor info, if we need to update.
   */
  private function _phpVersion () {
    //Min version.
    $min_version = version_compare(PHP_VERSION, $this->prerequisites['php']['min'], '>=');
    $this->assertEqual($min_version, TRUE, __('Your minimum version of PHP *must* be greater than %s', $this->prerequisites['php']['min']));
    //Max version.
    $max_version = version_compare(PHP_VERSION, $this->prerequisites['php']['max'], '<');
    $this->assertEqual($max_version, TRUE, __('Your minimum version of PHP *must* be less than %s', $this->prerequisites['php']['max']));
  }

  /**
   * @name _phpExtensions
   *  Checks for prerequisites.
   */
  private function _phpExtensions () {
    $haystack = get_loaded_extensions();
    $extensions = $this->prerequisites['extensions'];
    foreach ($extensions as $needle) {
      $result = in_array($needle, $haystack);
      $expected = true;
      $message = __('The extension %s is missing. You must have this extension enabled to continue.', $needle);
      $this->assertEqual($result, $expected, $message);
    }
  }

  /**
   * Config Tests.
   */

  /**
   * @name _checkConfig()
   *  This will check that you have the base default configuration file present.
   */
  private function _checkConfig() {
    //Settings Directory.
    $directory = APP.$this->prerequisites['config']['directory'];
    $name = $this->prerequisites['config']['name'];
    $ext = $this->prerequisites['config']['ext'];
    //Look for default file.
    $default_settings = $directory.$name.$ext;
    $result = file_exists($default_settings);
    $expected = true;
    $message = __('%s does not exist. You mut at a minimum have a %s%s configuration file.', $default_settings, $name, $ext);
    $this->assertEqual($result, $expected, $message);
  }

  /**
   * @name _loadConfig ()
   *  This will go through and test the configs that have been created.
   *  @internal - if this passes testing, but still does not work - then you will
   *    need to check the application tests, to make sure there are no errors when
   *    starting the application, as these will be code errors at this point.
   */
  private function _loadConfig () {
    $directory = APP.$this->prerequisites['config']['directory'];
    $regex = $this->prerequisites['config']['regex'];
    //Scan the settings directory.
    $folder = new Folder($directory);
    $files = $folder->find($regex);
    //Check that we have files.
    $result = !empty($files);
    $expected = true;
    $message = __('Could not find *any* config files. Please make sure you have configuration files added.');
    $this->assertEqual($result, $expected, $message);
    if (!empty($files)) {
      foreach ($files as $file) {
          $hash = str_replace($ext, '', $file);
          $local_config[$hash] = array(
            'path' => $directory.$file,
          );
      }
      //Create a config reader for our files, that follow our syntax.
      $reader = new PhpReader($directory);
      Configure::config('LocalSettings', $reader);
      //Always load the default settings, then merge the additional settings, so we
      //  can set a master default, and have total control over the configuration options.
      //  Read config files from app/Config/Local/(default|develop)\.config .
      //If we have come this far, then we actually check that the configs can be loaded.
      $config = array();
      foreach ($local_config as $key => $value) {
        $loaded = $reader->read($key, 'LocalSettings', TRUE);
        $result = is_array($loaded);
        $expected = true;
        $help = 'http://book.cakephp.org/2.0/en/development/configuration.html#PhpReader';
        $message = __('Configuration file not correct for %s. Please check that
          this file is readable, and is in the proper syntax with $config. %s', $key, $help);
        $this->assertEqual($result, $expected, $message);
        if ($result === true) {
          //Merge config.
          $config = Hash::merge($config, $loaded);
        }
      }
      //After we have loaded the Config, we check the config for syntax.
      $this->_checkConfigSyntax($config);
      //Test Cache settings.
      $this->_testCache($config);
      //Test Security Keys.
      $this->_testSecurityKeys($config);
      //Test DB File exists, and has Connectivity.
//      $this->_testDB($config); - This has been deprecated, as we have no DB requirements.
      //Test QNS has connectivity.
      $this->_testQNS($config);
    }
  }

  /**
   * @todo - document me.
   */
  private function _checkConfigSyntax (array $config) {
    // @todo - write validation for all config options.
    $required = $this->prerequisites['config']['required'];
    foreach ($required as $key => $value) {
      // @todo - allowed values from array().
      $hashGet = Hash::get($config, $key);
      $message = __('Configuration syntax is not valid for %s with value %s. Please fix your config file.
        Expected values are %s', $key, $hashGet, implode("\r\n", $value));
      //Check against values we are expecting.
      $result = (empty($hashGet)) ? FALSE : TRUE;
      if (!empty($value)) {
        if (in_array($hashGet, $value)) {
          //This will double check that if we have a zero, or null, or equiv
          // we can start to make some intelligent choices on values.
          $result = TRUE;
        }
      }
      //We are expecting this to NOT be null
      $expected = TRUE;
      $this->assertEqual($result, $expected, $message);
    }
  }

  /**
   * Cache Tests.
   */

  /**
   * @name _testCache ()
   *  The RESTful service needs to be very aware of caching, so at this moment
   *    I would like the caching service to be *mandatory*. You need to at the
   *    bare minumum 'File', 'Apc', 'Memcache'
   */
  private function _testCache (array $config) {
    // @todo - If we have File - test that the file cache is writable.
    $engine = Hash::get($config, 'Cache.engine');
    $haystack = get_loaded_extensions();
    switch ($engine) {
      //Check that the Memcache and Memcached extensions are installed.
      case 'Memcache':
        $extensions = array('memcache');
        break;
      //Test that the Apc extension is installed
      case 'Apc':
        $extensions = array('apc');
        break;
    }
    //Check that extensions are installed.
    foreach ($extensions as $needle) {
      $result = in_array($needle, $haystack);
      $expected = true;
      $message = __('The extension %s is missing. You must have this extension enabled to continue.', $needle);
      $this->assertEqual($result, $expected, $message);
    }
    //Do a quick read, write, check, delete on Cache functionality.
    $key = 'MainSystemTest';
    $result = Cache::write($key, 'Test Data', 'default');
    $expected = true;
    $message = __('Could not write to the cache. Please check your settings, and make sure your cache server is working
      and accessible by this program.');
    $this->assertEqual($result, $expected, $message);
    //Read Cache. This is reversed, we DO NOT want to get a false.
    $result = Cache::read($key, 'default');
    $expected = false;
    $message = __('Could not read from the cache. Please check your settings, and make sure your cache server is working
      and accessible by this program.');
    $this->assertNotEqual($result, $expected, $message);
    //Delete Cache.
    $result = Cache::delete($key, 'default');
    $expected = true;
    $message = __('Could not delete cache item %s. Please check your settings, and make sure your cache server is working
      and accessible by this program.', $key);
    $this->assertEqual($result, $expected, $message);
  }

  /**
   * Security Tests.
   */

  /**
   * @name _testSecurityKeys ()
   *  Tesst security keys for base Cake test,
   *  then make sure we have config options set for this.
   *  These will have to invoke a config, load it, and then unload the
   *  config.
   *
   *  @see testMainConfig
   */
  private function _testSecurityKeys (array $config) {
    //Salt.
    $salt = Hash::get($config, 'Security.salt');
    $result = ($salt == 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');
    $expected = false;
    $message = __('Please change Security.salt from the default values, as these need to be unique to your install.');
    $this->assertEqual($result, $expected, $message);
    //Cipher Seed.
    $cipher = Hash::get($config, 'Security.cipherSeed');
    $result = ($cipher == '76859309657453542496749683645');
    $expected = false;
    $message = __('Please change Security.cipherSeed from the default values, as these need to be unique to your install.');
    $this->assertEqual($result, $expected, $message);
    //Security key. 32 byte.
    $key = Hash::get($config, 'Security.key');
    $result = ($key == '4KEi?92bR-bet#wL@jxxK X6VR[E3Ltc');
    $expected = false;
    $help = 'http://www.csgnetwork.com/wepgeneratorcalc.html';
    $message = __('Please change Security.key (256 Bit Key) 32 byte ASCII key from the default values, as these need to be unique to your install.
      See: %s for more information.', $help);
    $this->assertEqual($result, $expected, $message);
  }

  /**
   * Database Tests.
   */

  /**
   * @name _testDB (array $config)
   *  Test DB files exist, and can connect.
   * @param array $config
   */
  private function _testDB (array $config) {
    //Check file exists.
    $result = file_exists(APP . 'Config' . DS . 'database.php');
    $expected = true;
    $message = __('Could not find database.php. This file is mandatory.');
    $this->assertEqual($result, $expected, $message);
    //Check that we can connect to the DB.
    // Just looking for default here.
    $default = Hash::get($config, 'LocalConfig.DataSource.default');
    $result = (empty($default)) ? FALSE : TRUE;
    $expected = true;
    $message = __('Could not find database connection in the config file. Please fix your config file. Add: LocalConfig.DataSource.default');
    $this->assertEqual($result, $expected, $message);
    if ($result === TRUE) {
      //Simple test for connectivity.
      //This reall is not an assertion, but test.
      try {
        $connected = ConnectionManager::getDataSource('default');
      } catch (Exception $connectionError) {
        $connected = false;
        $errorMsg = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')) {
          $attributes = $connectionError->getAttributes();
          if (isset($errorMsg['message'])) {
            $errorMsg .= '<br />' . $attributes['message'];
          }
        }
      }
      //If we have not flagged an exception, but still have a connection error,
      // show that.
      if (isset($connected->error)) {
        $errorMsg .= "\n\r" .$connected->error;
      }
      $result = ($connected && $connected->isConnected());
      $expected = true;
      $message = __('Could not connect to default Datasource. %s', $errorMsg);
      $this->assertEqual($result, $expected, $message);
    }
  }

  /**
   * @name _testQNS ($config)
   *  Test that the QNS connection is valid.
   * @param type $config
   */
  private function _testQNS ($config) {
    $default = Hash::get($config, 'LocalConfig.DataSource.qns');
    $result = (empty($default)) ? FALSE : TRUE;
    $expected = true;
    $message = __('Could not find QNS connection in the config file. Please fix your config file. Add: LocalConfig.DataSource.qns');
    $this->assertEqual($result, $expected, $message);
    if ($result === TRUE) {
      //Simple test for connectivity.
      //This reall is not an assertion, but test.
      try {
        $connected = ConnectionManager::getDataSource('qns');
      } catch (Exception $connectionError) {
        $connected = false;
        $errorMsg = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')) {
          $attributes = $connectionError->getAttributes();
          if (isset($errorMsg['message'])) {
            $errorMsg .= '<br />' . $attributes['message'];
          }
        }
      }
      //If we have not flagged an exception, but still have a connection error,
      // show that.
      if (isset($connected->error)) {
        $errorMsg .= "\n\r" .$connected->error;
      }
      $result = ($connected && $connected->connected == TRUE);
      $expected = true;
      $message = __('Could not connect to QNS Datasource. %s', $errorMsg);
      $this->assertEqual($result, $expected, $message);
    }
  }

  //End.
}