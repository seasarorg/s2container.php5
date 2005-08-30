<?php
error_reporting(E_ALL);
require_once(dirname(dirname(dirname(__FILE__))) . '/unit.inc.php');
define('PHPUnit2_MAIN_METHOD', '');
require_once('simpletest/unit_tester.php'); 
require_once('simpletest/reporter.php'); 

require_once 'bar/BarLogicTest.class.php';
require_once 'bar/BarAllTest.class.php';

require_once 'foo/FooLogicTest.class.php';
require_once 'foo/FooAllTest.class.php';

$test = new GroupTest('All Test');
$test->addTestCase(BarAllTest::group());
$test->addTestCase(FooAllTest::group());

if (TextReporter::inCli()) {
    print "\n\n============================\n";
    exit ($test->run(new TextReporter()) ? 0 : 1);
}
$test->run(new HtmlReporter()); 
?>