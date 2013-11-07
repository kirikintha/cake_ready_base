<?php
/**
 * @file SelfTestTask.php
 *  This shell runs maintenance tasks that you define as tasks in the tasks folder.
 */

class SelfTestTask extends AppShell {

  /**
   * @name execute()
   *  Prune Files.
   */
	function execute() {
    $this->_info(__('Starting Self Test'));
    //Dispatch Self Test.
    $this->dispatchShell('test app All');
	}

  //End.
}