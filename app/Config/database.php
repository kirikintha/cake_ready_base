<?php
/**
 * @file database.php
 *  This file handles Db connections via the local configuration files.
 */
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => '',
		'password' => '',
		'database' => '',
		'prefix' => '',
		'encoding' => 'utf8',
	);

	public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => '',
		'password' => '',
		'database' => '',
		'prefix' => '',
		'encoding' => 'utf8',
	);

  /**
   * This looks for datasources. If the datasource is required (ie === TRUE), then
   *  then it will throw a fatal exception and exit().
   * @throws Exception
   */
	public function __construct() {
    //Read configuration.
    foreach (Configure::read('LocalConfig.DataSource') as $key => $datasource) {
      //Get datasource from Config.
      try {
        if(!empty($datasource)) {
          if (isset($this->{$key})) {
            $this->{$key} = Hash::merge($this->{$key}, $datasource);
          } else {
            $this->{$key} = $datasource;
          }
        } else {
          //throw exception if data-source is required, but not used.
          throw new Exception(__('Could not find a datasource, please add a valid datasource
            to LocalConfig.DataSource.%s in your config options.', $key));
        }
      }
      catch (Exception $e) {
        echo $e->getMessage();
        exit();
      }
    }
	}

  //End.
}
