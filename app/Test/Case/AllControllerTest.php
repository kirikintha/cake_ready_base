<?php
/**
 * @file AllControllerTest.php
 *  This will run all the controller tests.
 */
class AllControllerTest extends CakeTestSuite {

  /**
   * @name suite
   *  This runs all the tests for the models.
   * @return \CakeTestSuite
   */
  public static function suite() {
    $suite = new CakeTestSuite('All controller tests');
    $suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
    return $suite;
  }

  //End.
}
