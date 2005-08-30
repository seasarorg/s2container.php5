<?php
error_reporting(E_ALL);
require_once(dirname(dirname(__FILE__)) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/extensionUnit.inc.php');

$test = &new GroupTest('All S2SimpleTestCase tests');
$test->addTestFile(TEST_DIR . '/s2container.php5/org/seasar/extension/unit/simpletest/S2SimpleTestCaseTests.class.php');

if (TextReporter::inCli()) {
    print "\n\n============================\n";
    exit ($test->run(new TextReporter()) ? 0 : 1);
}
$test->run(new HtmlReporter()); 

?>