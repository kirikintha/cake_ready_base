<?php
/**
 * @file KrumoHelper.php
 *  Adds krumo debugging to CakePHP. 
 */

//Load Krumo Library.
App::import('Vendor', 'krumo', false, array(), 'krumo/class.krumo.php');

//Set Default helpers.
App::uses('Helper', 'View');

/**
 * Class KrumoHelper
 *  This helper allows us to use the krumo function, or it's shorthand functions:
 *  pr();
 *  kpr(); 
 */
class KrumoHelper extends Helper { 
  
  /**
   * When cunstructing this class, we add the krumo class. 
   */
  function __construct() {
    if (class_exists('krumo')) {
      if(Configure::read('debug') != 0) { 
        krumo::enable(); 
      } else { 
        krumo::disable();
      } 
    } else {
      if(Configure::read('debug') != 0) { 
        die('Cannot find krumo, something is wrong.');
      }
    }
  }

}

if (function_exists('krumo')) {
  //Breakpoint. returns krumo, with an exit, to break at that point.
  if (!function_exists('kbr')) {
    function kbr($foo = NULL) {
      krumo($foo);
      exit();
    }
  }
  if (!function_exists('pr')) {
    //Replace pr() debug kit function.
    function pr($foo = NULL) {
      krumo($foo);
    }
  }
  //kpr id a common function in other CMS implemenetations, so we can add that too.
  if (!function_exists('kpr')) {
    function kpr($foo = NULL) {
      krumo($foo);
    }
  }
}