<?php
error_reporting(E_ALL);
require_once(dirname(dirname(__FILE__)) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/extensionUnit.inc.php');

define('PHPUnit2_MAIN_METHOD', '');
require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';

print "\n\n============================\n";
$suite  = new PHPUnit2_Framework_TestSuite('S2PHPUnit2TestCaseTests');
PHPUnit2_TextUI_TestRunner::run($suite);

?>