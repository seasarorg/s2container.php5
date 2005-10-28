<?php
error_reporting(E_ALL);
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/unit.inc.php');
define('PHPUnit2_MAIN_METHOD', 'FooAllTest::main()');
require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';


require_once 'FooLogicTest.class.php';
require_once 'FooAllTest.class.php';

print "\n\n============================\n";
FooAllTest::main();
?>