<?php
class MySQLConnectTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testNativeMySQL() {
       
        print __METHOD__ . "\n";
       
        $connect = mysql_connect('localhost:3306',
                                 'hoge',
                                 'hoge');  
        mysql_select_db('test');
        
        $result = mysql_query('select * from dept where deptno = 10;');  
        $rows = mysql_fetch_row($result);
        $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));
        
        print "\n";
    }

    function testPearDB() {
       
        print __METHOD__ . "\n";
       
        $dbUser = 'hoge';
        $dbPass = 'hoge';
        $dbHost = 'localhost';
        $dbName = 'test';
        $dbType = 'mysql';
        
        $db = DB::connect("$dbType://$dbUser:$dbPass@$dbHost/$dbName");
        $result = $db->query('select * from dept where deptno = 10;'); 
        $rows = $result->fetchRow();
        $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));
        $db->disconnect();
        
        print "\n";
   }
}
?>