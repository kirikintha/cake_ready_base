<?php
/**
 * @file MainSystemTestTask.php
 *  This shell runs maintenance tasks that you define as tasks in the tasks folder.
 */

class MainSystemTestTask extends AppShell {

  /**
   * @name execute()
   *  Run main system tests.
   */
	function execute() {
    $this->_info(__('Main System Test'));
    //Dispatch Self Test.
    $this->dispatchShell('test app Controller/MainSystem');
	}

  //End.
}