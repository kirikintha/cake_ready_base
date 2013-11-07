<?php
/**
 * @file default.ctp
 *  This is the default layout.
 */
$cakeDescription = __d('cake_dev', 'CakePHP Ready Base');
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
      <?php echo $cakeDescription ?>:
      <?php echo $title_for_layout; ?>
    </title>
    <?php
      echo $this->Html->meta('icon');
      echo $this->Html->meta('viewport', null, array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'), false);
      echo $this->fetch('meta');
      echo $this->Html->css('bootstrap.min');
      echo $this->Html->css('styles');
      echo $this->fetch('css');
      echo $this->Html->script('jquery-1.10.2.min');
      echo $this->Html->script('bootstrap.min');
      echo $this->fetch('script');
    ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body id="default" class="default">
    <header class="navbar navbar-inverse navbar-fixed-top" role="banner">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="/">Home</a>
        </div>
      </div>
    </header>
    <!-- Container -->
    <div id="main" class="container">
      <!-- Row -->
      <div class="row">
        <!-- Left Column -->
        <div class="col-md-3 sidebar-left">
          <p>Sidebar</p>
        </div>
        <!-- /Left Column -->
        <!-- Main Content -->
        <div id="content" class="col-md-9 content">
          <?php echo $this->Session->flash(); ?>
          <?php echo $this->fetch('content'); ?>
        </div>
        <!-- /Main Content -->
      </div>
      <!-- /Row -->
    </div>
    <!-- /Container -->
    <footer>
      <div class="container">
        <div id="copyright" class="pull-right">&copy; <?php echo date('Y', strtotime('now')); ?></div>
      </div>
    </footer>
  </body>
</html>
