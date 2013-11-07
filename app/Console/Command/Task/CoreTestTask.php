<?php
/**
 * @file CoreTestTask.php
 *  This shell runs
 */

class CoreTestTask extends AppShell {

  /**
   * @name execute()
   *  Prune Files.
   */
	function execute() {
    $this->_info(__('Starting Core Test'));
    //Dispatch Core configuration tests.
    $this->dispatchShell('test core Core/Configure');
	}

  //End.
}