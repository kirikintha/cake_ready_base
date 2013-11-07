<?php
/**
 * @file MakeConfigFileTask.php
 *  This shell runs maintenance tasks that you define as tasks in the tasks folder.
 */

class MakeConfigFileTask extends AppShell {

  /**
   * @name execute()
   *  Prune Files.
   */
	function execute() {
    $this->_info(__('Starting your config.'));
    //Get app/Config/Local/default.php
    //Read it, load $config.
    // Ask your questions,
    // Confirm that they want to write that config, or start over.
    // If Confirmed - write me.
	}

  //End.
}