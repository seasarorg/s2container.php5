<?php
//error_reporting(E_ALL & E_STRICT);

require_once(dirname(__FILE__) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/container.inc.php');

//session_start();

$test = new GroupTest('All S2CONTAINER_PHP5 tests');
$aopAllTest = AopAllTest::group();
$test->addTestCase($aopAllTest);
$beansAllTest = BeansAllTest::group();
$test->addTestCase($beansAllTest);
$containerAllTest = ContainerAllTest::group();
$test->addTestCase($containerAllTest);
$exceptionAllTest = ExceptionAllTest::group();
$test->addTestCase($exceptionAllTest);
$utilAllTest = UtilAllTest::group();
$test->addTestCase($utilAllTest);

if (TextReporter::inCli()) {
    print "\n\n============================\n";
    exit ($test->run(new TextReporter()) ? 0 : 1);
}
$test->run(new HtmlReporter()); 

?>