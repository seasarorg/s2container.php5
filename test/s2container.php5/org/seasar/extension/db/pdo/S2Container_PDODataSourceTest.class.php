<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// $Id:$
/**
 * @package org.seasar.framework.extension.db.pdo
 * @author klove
 */
class S2Container_PDODataSourceTest
    extends PHPUnit_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
        if(!defined('DB_DIR_S2Conatiner_PDODataSource')){
            define('DB_DIR_S2Conatiner_PDODataSource',dirname(dirname(__FILE__)));
        }
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testDataSource() {
        $container = S2ContainerFactory::create(
                     DB_DIR_S2Conatiner_PDODataSource . '/pdo/pdo.dicon');
        $this->assertType('S2Container',$container);
        $ds = $container->getComponent('pdo.dataSource');
        $this->assertType('S2Container_PDODataSource',$ds);

        print("Data Source : ");
        print($ds);
        print "\n";
        $db = $ds->getConnection();
        $result = $db->query('select * from DEPT where deptno = 10;'); 
        $rows = $result->fetch(PDO::FETCH_NUM);
        $this->assertEquals($rows,array('10','ACCOUNTING','NEW YORK','0'));
        $db = null;
    }
}
?>
