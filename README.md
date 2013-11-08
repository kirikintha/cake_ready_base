[![CakePHP](http://cakephp.org/img/logo/cakephp_logo_125_trans.png)](http://www.cakephp.org)

CakePHP
=================

CakePHP is a rapid development framework for PHP which uses commonly known design patterns like Active Record, Association Data Mapping, Front Controller and MVC.
Our primary goal is to provide a structured framework that enables PHP users at all levels to rapidly develop robust web applications, without any loss to flexibility.

CakePHP Ready Base
=================

Download
----------------

To clone the Cake Ready Base using git, run the command:

	git clone https://github.com/kirikintha/cake_ready_base.git

To visit the Clone Ready Base:

	https://github.com/kirikintha/cake_ready_base

Setup Instructions
----------------

* Change into the directory where you cloned Base Ready and run `make clean`.

* Set up your configuration values in `Config/Local/default.php` but leave debug set to `0`.

* Set the timezone to `America/New York` or whatever your time zone equivalent is. Full list available here:

	<a href="http://www.php.net/manual/en/timezones.php" target="_blank">http://www.php.net/manual/en/timezones.php</a>

* Save the `Config/Local/default.php` file as `Config/Local/develop.php`. The `Config/Local/develop.php` file should be ignored in `.gitignore` before uploading the app to the production site.

* Set `debug` to `2` in `Config/Local/develop.php`.

Please Note
----------------

* The `Config/Local/default.php` file is the "production" version of your config, and should be present on both development and production sites.

* The `Config/Local/develop.php` file is the "development" version of your config, and should only be present on the development site.

* Whenever the `Config/Local/develop.php` file is present it will override your `Config/Local/default.php` file's settings.

Some Handy Links
----------------

[CakePHP](http://www.cakephp.org) - The rapid development PHP framework

[CookBook](http://book.cakephp.org) - THE CakePHP user documentation; start learning here!

[API](http://api.cakephp.org) - A reference to CakePHP's classes

[Plugins](http://plugins.cakephp.org/) - A repository of extensions to the framework

[The Bakery](http://bakery.cakephp.org) - Tips, tutorials and articles

[Community Center](http://community.cakephp.org) - A source for everything community related

[Training](http://training.cakephp.org) - Join a live session and get skilled with the framework

[CakeFest](http://cakefest.org) - Don't miss our annual CakePHP conference

[Cake Software Foundation](http://cakefoundation.org) - Promoting development related to CakePHP

Get Support!
------------

[#cakephp](http://webchat.freenode.net/?channels=#cakephp) on irc.freenode.net - Come chat with us, we have cake

[Google Group](https://groups.google.com/group/cake-php) - Community mailing list and forum

[GitHub Issues](https://github.com/cakephp/cakephp/issues) - Got issues? Please tell us!

[![Bake Status](https://secure.travis-ci.org/cakephp/cakephp.png?branch=master)](http://travis-ci.org/cakephp/cakephp)

![Cake Power](https://raw.github.com/cakephp/cakephp/master/lib/Cake/Console/Templates/skel/webroot/img/cake.power.gif)
