<?php
error_reporting(E_ALL);
require_once(dirname(dirname(__FILE__)) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/extensionUnit.inc.php');

$group = &new GroupTest('All S2SimpleTestCase tests');

require_once(TEST_DIR . '/s2container.php5/org/seasar/extension/unit/simpletest/S2SimpleTestCaseTests.class.php');
$test = new S2SimplTestCaseTests();
$group->addTestCase($test);

if (TextReporter::inCli()) {
    print "\n\n============================\n";
    exit ($group->run(new TextReporter()) ? 0 : 1);
}
$group->run(new HtmlReporter()); 

?>