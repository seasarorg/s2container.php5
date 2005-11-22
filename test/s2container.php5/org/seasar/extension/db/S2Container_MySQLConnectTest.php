<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
/**
 * @package org.seasar.extension.db
 */
/**
 * @file S2Container_MySQLConnectTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.extension.db
 * @class S2Container_MySQLConnectTest
 */
class MySQLConnectTests extends PHPUnit2_Framework_TestCase {

    /**
     * Construct Testcase
     */
    public function __construct() {
         parent::__construct();
    }

    /**
     * Setup Testcase
     */
    public function setUp() {
        parent::setUp();
    }

    /**
     * Clean up Testcase
     */
    public function tearDown() {
        parent::tearDown();
    }
            
    /**
     * testNativeMySQL
     * @return 
     */
    public function testNativeMySQL() {
       
        $connect = mysql_connect('localhost:3306',
                                 'hoge',
                                 'hoge');  
        mysql_select_db('test');
        
        $result = mysql_query('select * from dept where deptno = 10;');  
        $rows = mysql_fetch_row($result);
        $this->assertEquals($rows,array('10','ACCOUNTING','NEW YORK','0'));
    }
            
    /**
     * testPearDB
     * @return 
     */
    public function testPearDB() {
       
        $dbUser = 'hoge';
        $dbPass = 'hoge';
        $dbHost = 'localhost';
        $dbName = 'test';
        $dbType = 'mysql';
        
        $db = DB::connect("$dbType://$dbUser:$dbPass@$dbHost/$dbName");
        $result = $db->query('select * from dept where deptno = 10;'); 
        $rows = $result->fetchRow();
        $this->assertEquals($rows,array('10','ACCOUNTING','NEW YORK','0'));
        $db->disconnect();
   }
}
?>