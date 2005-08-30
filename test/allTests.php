<?php
error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/container.inc.php');

//session_start();

$test = new GroupTest('All S2CONTAINER_PHP5 tests');
$test->addTestCase(AopAllTest::group());
$test->addTestCase(BeansAllTest::group());
$test->addTestCase(ContainerAllTest::group());
$test->addTestCase(ExceptionAllTest::group());
$test->addTestCase(UtilAllTest::group());

if (TextReporter::inCli()) {
    print "\n\n============================\n";
    exit ($test->run(new TextReporter()) ? 0 : 1);
}
$test->run(new HtmlReporter()); 

?>