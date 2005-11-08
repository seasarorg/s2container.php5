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

foreach($packages as $pkgDir){
    requireOnce($pkgDir);
}
/*
__autoload('S2Container_DBSessionImpl');
__autoload('S2Container_SimpleDaoInterceptor');
__autoload('S2Container_DaoMetaDataFactoryImpl');

__autoload('S2Container_PearDBDataSource');
__autoload('S2Container_PearDBSqlHandler');
__autoload('S2Container_PearDBTxInterceptor');

__autoload('S2Container_ADOdbDataSource');
__autoload('S2Container_ADOdbSqlHandler');
__autoload('S2Container_ADOdbTxInterceptor');

__autoload('S2Container_PostgresDataSource');
__autoload('S2Container_PostgresSqlHandler');
__autoload('S2Container_PostgresTxInterceptor');

__autoload('S2Container_MySQLDataSource');
__autoload('S2Container_MySQLSqlHandler');
__autoload('S2Container_MySQLTxInterceptor');
*/
/*
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR .
         implode(PATH_SEPARATOR, $packages));
*/
?>