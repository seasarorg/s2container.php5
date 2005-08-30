<?php
error_reporting(E_ALL);
require_once(dirname(__FILE__) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/extensionDb.inc.php');

DbAllTest::main();

/*
define('HOME_DIR',dirname(dirname(__FILE__)));
require_once(HOME_DIR . '/conf/dbTest.inc.php');
require_once(CONF_DIR . '/simpletest.inc.php'); 

$packages = array(
    SRC_DIR,
    TEST_DIR . "/sample/db",
    TEST_DIR . "/sample/db/impl",
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/peardb"
    );
ini_set('include_path', ini_get('include_path') . 
         PATH_SEPARATOR . implode(PATH_SEPARATOR,$packages));
         
$test = &new GroupTest('All DB tests');
//$test->addTestFile(TEST_DIR . '/s2container.php5/org/seasar/extension/db/MySQLTests.class.php');
$test->addTestFile(TEST_DIR . '/s2container.php5/org/seasar/extension/db/peardb/PearDBTests.class.php');
$test->addTestFile(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/ADOdbTests.class.php');
$test->addTestFile(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/MySQLTests.class.php');
//$test->addTestFile(TEST_DIR . '/s2container.php5/org/seasar/extension/db/postgres/PostgresTests.class.php');

if (TextReporter::inCli()) {
    print "\n\n============================\n";
    exit ($test->run(new TextReporter()) ? 0 : 1);
}
$test->run(new HtmlReporter()); 
*/
?>