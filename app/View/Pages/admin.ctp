<?php
/**
 * Admin Page - shows GUI Diagnostics.
 */
?>
<!-- Core CakePHP Checks -->
<div id="core" class="row">
  <h3>Core Configuration</h3>
  <p>
    <div class="alert alert-info">
      <a href="http://cakephp.org/changelogs/<?php echo Configure::version(); ?>" target="_blank"><?php echo __('Using CakePHP Version: %s', Configure::version()); ?> </a>
    </div>
  </p>
  <?php
  if (Configure::read('debug') > 0):
    Debugger::checkSecurityKeys();
  endif;
  ?>
  <p>
  <?php
    //@todo - this should be a global variable, so we can adjust for test variables
    // and have it move all the way down the stack. Confugure::read('Sdk.phpVersion');
    $version = array(
      'min' => '5.3.20',
      'max' => '5.6.0',
    );
    if (version_compare(PHP_VERSION, $version['min'], '>=')
        || version_compare(PHP_VERSION, $version['max'], '<')):
      echo '<div class="alert alert-success">';
      echo __d('cake_dev', 'Your version of PHP is '. $version['min'] .' or higher.');
      echo '</div>';
    else:
      echo '<div class="alert alert-danger">';
      echo __d('cake_dev', 'Your version of PHP is too low. You need PHP '. $version .' or higher to use CakePHP.');
      echo '</div>';
    endif;
  ?>
  </p>
  <p>
    <?php
      if (is_writable(TMP)):
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'Your tmp directory is writable.');
        echo '</div>';
      else:
        echo '<div class="alert alert-danger">';
        echo __d('cake_dev', 'Your tmp directory is NOT writable.');
        echo '</div>';
      endif;
    ?>
  </p>
  <p>
    <?php
      $settings = Cache::settings();
      if (!empty($settings)):
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'The %s is being used for core caching. To change the config edit APP/Config/Local ', '<em>'. $settings['engine'] . 'Engine</em>');
        echo '</div>';
      else:
        echo '<div class="alert alert-danger">';
        echo __d('cake_dev', 'Your cache is NOT working. Please check the settings in APP/Config/Local');
        echo '</div>';
      endif;
    ?>
  </p>
  <p>
    <?php
      $filePresent = null;
      if (file_exists(APP . 'Config' . DS . 'database.php')):
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'Your database configuration file is present.');
        $filePresent = true;
        echo '</div>';
      else:
        echo '<div class="alert alert-danger">';
        echo __d('cake_dev', 'Your database configuration file is NOT present.');
        echo '<br/>';
        echo __d('cake_dev', 'Rename APP/Config/database.php.default to APP/Config/database.php');
        echo '</div>';
      endif;
    ?>
  </p>
  <?php
  if (isset($filePresent) && Configure::check('LocalConfig.DataSource')):
    App::uses('ConnectionManager', 'Model');
    foreach (Configure::read('LocalConfig.DataSource') as $key => $value) :
      try {
        $connected = ConnectionManager::getDataSource($key);
      } catch (Exception $connectionError) {
        $connected = false;
        $errorMsg = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')) {
          $attributes = $connectionError->getAttributes();
          if (isset($errorMsg['message'])) {
            $errorMsg .= '<br />' . $attributes['message'];
          }
        }
      }
      if ($connected && $connected->connected == TRUE):
        echo '<div class="alert alert-success">';
          echo __d('cake_dev', 'SDK is able to connect to %s.', ucwords($key));
        echo '</div>';
      else:
        echo '<div class="alert alert-danger">';
          echo __d('cake_dev', 'SDK is unable to connect to %s. <br /> %s', ucwords($key), $connected->error);
          echo $errorMsg;
        echo '</div>';
      endif;
    endforeach;
  ?>
  <?php else:
    echo '<div class="alert alert-danger">';
    echo '<p>Could not find any datasources. You must have at minimum, a qns datacsource present.</p>';
    echo '</div>';
  ?>
  <?php endif; ?>
  <?php
    App::uses('Validation', 'Utility');
    if (!Validation::alphaNumeric('cakephp')) {
      echo '<p><div class="alert alert-danger">';
        echo __d('cake_dev', 'PCRE has not been compiled with Unicode support.');
        echo '<br/>';
        echo __d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
      echo '</div></p>';
    }
  ?>
  <?php
    //Krumo support.
    if (class_exists('krumo')) {
      echo '<div class="alert alert-success">';
      echo __d('cake_dev', 'Krumo is installed correctly.');
      echo '</div>';
    } else {
      echo '<div class="alert alert-info">';
      echo __d('cake_dev', 'Krumo is not installed.');
      echo '</div>';
    }
  ?>
</div>
<!-- PHP Extensions -->
<div id="php-ext-loaded" class="row">
  <h3>Loaded Extensions</h3>
  <?php
    //Echo Installed extensions.
    $extensions = get_loaded_extensions();
    natcasesort($extensions);
    foreach ($extensions as $extension) {
      echo '<div class="col-md-2"><div class="well well-sm">';
      echo $this->Html->link(__('<small>%s</small>', $extension), __('#module_%s', $extension), array(
        'escape' => false,
      ));
      echo '</div></div>';
    }
  ?>
</div>
<!-- PHP Info -->
<div id="php-info" class="row">
  <h3>PHP Info</h3>
  <div class="well">
    <p>
      <?php
        ob_start();
        phpinfo();
        $phpinfoHtml = ob_get_contents();
        ob_end_clean();
        //Echo Contents.
        echo preg_replace('%^.*<body>(.*)</body>.*$%ms','$1',$phpinfoHtml);
      ?>
    </p>
  </div>
</div>