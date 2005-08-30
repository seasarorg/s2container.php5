<?php
require_once('DB.php');
require_once("adodb/adodb-exceptions.inc.php"); 
require_once("adodb/adodb.inc.php");

$packages = array(
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/" ,
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/adodb" ,
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/mysql" ,
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/peardb" ,
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/postgres",
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/impl"
);

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR .
         implode(PATH_SEPARATOR, $packages));
?>