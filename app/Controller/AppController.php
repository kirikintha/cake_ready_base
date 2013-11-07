<?php
/**
 * @file AppController.php
 *  This is the main "global" application file for this application. It is imperative
 *  that methods that need to be re-used are placed here.
 */
App::uses('Controller', 'Controller');
//Global utilities.
App::uses('Sanitize', 'Utility');
App::uses('CakeNumber', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

  //Add global components.
  public $components = array(
    'RequestHandler',
    'Session',
    // @todo - do we need to add CSRF or HTTPS switches?
    'Security' => array(
      'csrfUseOnce' => false,
    ),
    //Krumo
    'Krumo',
  );

  //Add global helpers.
  // Krumo - debugging.
  // FormApi - form handleing.
  var $helpers = array(
    'App',
    'Krumo',
    'Html',
    'Text',
    'Session',
  );

  //Pagination global properties.
  public $paginate = array(
    'limit' => 10,
    'order' => array(),
    'fields' => array(),
  );

  /**
   * @name beforeFilter()
   *  This runs the system stack, before we hand it off to controllers.
   */
  public function beforeFilter() {
    //Security Settings.
    self::_setSecurity();
    //Set any additional environmental values.
    self::_setEnvironment();
  }

  /**
   * SDK Api functionality.
   */

  public function _setSecurity() {
    $this->Security->unlockedActions = array(
      'index', 'add', 'update', 'delete'
    );
    $this->Security->blackHoleCallback = '_blackhole';
    //Require SSL - this is the default option.
    if (Configure::read('LocalConfig.SSL') === TRUE) {
      $this->Security->requireSecure();
    }
  }

  /**
   * @name _blackhole($type)
   *  This is the Cake callback for black holese.
   * @param type $type
   */
  public function _blackhole($type) {
    switch ($type) {
      //If we blackhole SSL.
      case 'secure':
        self::_blackHoleSecure($type);
        break;
      //If we are requiring an authorization.
      case 'auth':
      default:
        self::_blackHoleAuth($type);
        break;
    }
  }

  /**
   * @name _blackHoleSecure ()
   *  This is if we need to trigger an SSL error.
   */
  public function _blackHoleSecure ($type = NULL) {
    //@todo - add blackhole exception here.
  }

  /**
   * @name _blackHoleAuth ()
   *  Auth and default errors are auth errors. We can consider anything else an
   *  authorization error.
   */
  public function _blackHoleAuth ($type = NULL) {
    //@todo - add blackholed auth exception.
  }

  /**
   * @name _setEnvironment()
   *  This sets any app environment variables we can pass through the AppController
   *  object.
   */
  public function _setEnvironment() {
    self::_setBaseUrl();
    self::_setHost();
    self::_setPort();
    self::_setServer();
    self::_setRemoteAddress();
    self::_setRemotePort();
    self::_setServerAdmin();
  }

  /**
   * @name _setBaseUrl ()
   *  Set base Url, reversing the route.
   */
  public function _setBaseUrl () {
    $this->request->baseUrl = Router::reverse(array(), TRUE);
  }

  /**
   * @name _setHost ()
   *  This will set the host for you.
   *  Order of preference SERVER_NAME -> HTTP_HOST -> SERVER_ADDR
   *  This will also look for proxy requests.
   */
  public function _setHost () {
    $host = !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] :
      !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_ADDR'];
    $this->request->host = !empty($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $host;
  }

  /**
   * @name _setPort()
   *  look for the incoming port.
   */
  public function _setPort() {
    $this->request->port = !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : NULL;
  }

  /**
   * @name _setServer()
   *  Server Name. Look for proxies.
   */
  public function _setServer() {
    $this->request->server = !empty($_SERVER['HTTP_X_FORWARDED_SERVER']) ? $_SERVER['HTTP_X_FORWARDED_SERVER'] : $_SERVER['HTTP_HOST'];
  }

  /**
   * @name _setRemoteAddress ()
   *  Ip address. Look for proxies.
   *  Order of preference HTTP_CLIENT_IP -> HTTP_X_FORWARDED_FOR -> REMOTE_ADDR
   */
  public function _setRemoteAddress () {
    $this->request->remoteAddr = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] :
      !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
  }

  /**
   * @name _setRemotePort()
   *  Remote port.
   *  Order of preference HTTP_X_PORT -> REMOTE_PORT
   */
  public function _setRemotePort() {
    $this->request->remotePort = !empty($_SERVER['HTTP_X_PORT']) ? $_SERVER['HTTP_X_PORT'] :
      !empty($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : NULL;
  }

  /**
   * @name _setServerAdmin ()
   */
  public function _setServerAdmin () {
    //Server admin
    $this->request->serverAdmin = !empty($_SERVER['SERVER_ADMIN']) ? $_SERVER['SERVER_ADMIN'] : 'server.admin@localhost';
  }

  //End.

}
