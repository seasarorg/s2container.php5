<?php
error_reporting(E_ALL);
require_once(dirname(__FILE__) . '/conf/test.ini.php');
require_once(TEST_DIR . '/conf/container.inc.php');

UtilAllTest::main();
?>