<?php
//require_once('Zend/Test/PHPUnit/ControllerTestCase.php');

require_once('Zend/Application.php');

define('APPLICATION_PATH', dirname(dirname(dirname(__FILE__))) . '/application');
define('APPLICATION_ENV', 'testing');

$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap();

require_once(APPLICATION_PATH . '/configs/s2.php');

