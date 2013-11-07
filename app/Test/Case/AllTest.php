<?php
/**
 * @file AllTest.php
 *  This will run all the tests under /Case.
 */
class AllTest extends CakeTestSuite {

  /**
   * @name suite
   *  This runs all the tests.
   * @return \CakeTestSuite
   */
  public static function suite() {
    $suite = new CakeTestSuite('All test');
    $suite->addTestDirectoryRecursive(TESTS . 'Case');
    return $suite;
  }

  //End.
}
