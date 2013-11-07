<?php
/**
 * @file AllModelTest.php
 *  This will run all the controller tests.
 */
class AllModelTest extends CakeTestSuite {

  /**
   * @name suite
   *  This runs all the tests for the models.
   * @return \CakeTestSuite
   */
  public static function suite() {
    $suite = new CakeTestSuite('All model tests');
    $suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
    return $suite;
  }

  //End.
}
