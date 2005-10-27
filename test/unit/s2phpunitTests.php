<?php
error_reporting(E_ALL);
require_once(dirname(dirname(__FILE__)) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/extensionUnit.inc.php');

print "\n\n============================\n";
$suite  = new PHPUnit_TestSuite();
$suite->addTestSuite('S2PHPUnitTestCaseTests');
$result = PHPUnit::run($suite);
echo $result -> toString();         

?>