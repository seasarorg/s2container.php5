<?php
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))) . '/unit.inc.php');
require_once 'PHPUnit.php';

require_once 'bar/BarLogicTest.class.php';
require_once 'bar/BarAllTest.class.php';

require_once 'foo/FooLogicTest.class.php';
require_once 'foo/FooAllTest.class.php';

print "\n\n============================\n";
$suite  = new PHPUnit_TestSuite();
$barAllTest = BarAllTest::suite();
$suite->addTest($barAllTest);
$fooAllTest = FooAllTest::suite();
$suite->addTest($fooAllTest);
$result = PHPUnit::run($suite);
echo $result -> toString();         

?>