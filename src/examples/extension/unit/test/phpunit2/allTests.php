<?php
error_reporting(E_ALL);
require_once(dirname(dirname(dirname(__FILE__))) . '/unit.inc.php');
define('PHPUnit2_MAIN_METHOD', '');
require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';

require_once 'bar/BarLogicTest.class.php';
require_once 'bar/BarAllTest.class.php';

require_once 'foo/FooLogicTest.class.php';
require_once 'foo/FooAllTest.class.php';


print "\n\n============================\n";
$suite  = new PHPUnit2_Framework_TestSuite();
$barAllTest = BarAllTest::suite();
$suite->addTest($barAllTest);
$fooAllTest = FooAllTest::suite();
$suite->addTest($fooAllTest);
PHPUnit2_TextUI_TestRunner::run($suite);
?>