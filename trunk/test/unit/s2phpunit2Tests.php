<?php
require_once(dirname(dirname(__FILE__)) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/extensionUnit.inc.php');

print "\n\n============================\n";
$suite  = new PHPUnit2_Framework_TestSuite('S2PHPUnit2TestCaseTests');
PHPUnit2_TextUI_TestRunner::run($suite);

?>