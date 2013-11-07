<?php
/**
 * @file TestSuiteShell.php
 *  This tells the user what they can test in the system.
 *  Lots of tests.
 */

class TestSuiteShell extends AppShell {

  //Tasks - this lets us make sure that nothing else but tests can be run through
  // this interface.

  public $tasks = array('MainSystemTest', 'CoreTest', 'ApplicationTest', 'MakeConfigFile');

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
   *  Runs all tasks assigned.
   */
	function main() {
    $options = array(
      'C',
      'M',
      'S',
      'I',
    );
    $descriptions = array(
      '[C]ore (CakePHP Configuration)',
      '[M]ain System (Environment Tests)',
      '[S]elf Test (Application Tests)',
      '[I]nstall (Make Config File)',
    );
    $msg = __("Which test would you like to run?\n\r%s", implode("\r\n", $descriptions));
    $selection = $this->in($msg, $options);
    // Run Selection.
    $this->_runSelection($selection);
	}

  /**
   * @name _runSelection ($selection)
   *  Run the test suite selection.
   * @param type $selection
   */
  public function _runSelection ($selection) {
    switch ($selection) {
      case 'S':
        // Self Test
        $name = 'SelfTest';
        break;
      case 'M':
        // Main System Test
        $name = 'MainSystemTest';
        break;
      case 'C':
        // Core Test CakePHP Core.
        $name = 'CoreTest';
        break;
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