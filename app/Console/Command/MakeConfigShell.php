<?php
/**
 * @file MakeConfigShell.php
 *  This will run the Make Config File task.
 */

class MakeConfigShell extends AppShell {

  //Tasks - this lets us make sure that nothing else but tests can be run through
  // this interface.

  public $tasks = array('MakeConfigFile');

  /**
   * @name startup()
   * @internal - Note, that you should not clear configs here, just read them.
   */
  public function startup() {
    parent::startup();
    //Clear out any other input.
    $this->clear();
  }

  /**
   * @name main()
   *  Run the config maker task.
   */
	function main() {
    // Run Selection.
    $this->_runSelection('I');
	}

  /**
   * @name _runSelection ($selection)
   *  Run the test suite selection.
   * @param type $selection
   */
  public function _runSelection ($selection) {
    switch ($selection) {
      case 'I':
        // Make Config file
        $name = 'MakeConfigFile';
        break;
    }
    //Run Task.
    if ($this->hasTask($name)) {
      try {
        $success = $this->{$name}->execute();
      }
      catch (Exception $e) {
        $this->_error($e->getMessage());
      }
    } else {
      $this->_error('Task not found, please try again.');
    }
  }

  //End.
}