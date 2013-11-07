<?php
/**
 * @file KrumoComponent.php
 *  This replaces debug with krumo.
 */
App::uses('Component', 'Controller');

//Load Krumo Library.
App::import('Vendor', 'krumo', false, array(), 'krumo/class.krumo.php');

class KrumoComponent extends Component {
  //Initalize, and load Krumo.
  public function initialize(Controller $controller) {
    if (class_exists('krumo')) {
      if(Configure::read('debug') != 0) {
        krumo::enable();
      } else {
        krumo::disable();
      }
    } else {
      if(Configure::read('debug') != 0) {
        throw new NotImplementedException('Could not find Krumo. Turn your debug settings to 0, or install krumo in app/vendors');
      }
    }
  }
}

//Custom functions, outside of Cake, for shorthand.
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

//End.