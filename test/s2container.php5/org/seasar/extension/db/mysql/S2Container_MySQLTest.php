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
 * @package org.seasar.extension.db.mysql
 */
/**
 * @file S2Container_MySQLTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.extension.db.mysql
 * @class S2Container_MySQLTest
 */
class MySQLTests extends PHPUnit2_Framework_TestCase {

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
     * testDataSource
     * @return 
     */
    public function testDataSource() {

        $container = S2ContainerFactory::create(S2CONTAINER_PHP5 . '/org/seasar/extension/db/mysql.dicon');
        $this->assertNotNull($container);
        $ds = $container->getComponent('mysql.dataSource');
        $this->assertNotNull($ds);

        print("Data Source : ");
        print($ds);
        $db = $ds->getConnection();
        $result = mysql_query('select * from dept where deptno = 10;',$db); 
        $rows = mysql_fetch_array($result,MYSQL_NUM);
        $this->assertEquals($rows,array('10','ACCOUNTING','NEW YORK','0'));
        mysql_free_result($result);
        mysql_close($db);
    }
            
    /**
     * testComponent
     * @return 
     */
    public function testComponent() {

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testMySQL.dicon');
        $dao = $container->getComponent('deptDao');
        $this->assertNotNull($dao);
        
        $rows = $dao->findDeptByDeptno(10);
        $this->assertEquals($rows,array('10','ACCOUNTING','NEW YORK','0'));
    }
            
    /**
     * testTxAspect
     * @return 
     */
    public function testTxAspect() {

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testTx.dicon');

        $dao = $container->getComponent('deptDao');
        $this->assertNotNull($dao);
        $rows = $dao->findDeptByName('ACCOUNTING');
        $this->assertEquals($rows,array('10','ACCOUNTING','NEW YORK','0'));
    }
            
    /**
     * testTxAspect
     * @return 
     */
    public function testTxAspectUpdate() {

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testTx.dicon');

        $dao = $container->getComponent('deptDao');
        $this->assertNotNull($dao);
        try{
            $ret = $dao->updateDnameByDeptno('40','SALES');
            $this->assertEquals($ret,null);
        }catch(Exception $e){
        }
    }
/* */
    function testDao() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testDao.dicon');
        $dao = $container->getComponent('deptDao');
        $rows = $dao->findDeptByName('ACCOUNTING');
        $this->assertEquals($rows[0],array('10','ACCOUNTING','NEW YORK','0'));
    }
            
    /**
     * testDaoBean
     * @return 
     */
    public function testDaoBean() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testDaoBean.dicon');
        $dao = $container->getComponent('empDao');
        $rows = $dao->findEmpByEname('ALLEN');
        $this->assertType('EmpDto', $rows[0]);
        $this->assertEquals($rows[0]->getEmpno(),7499);

        $rows = $dao->findEmpByEnameQuery('ALLEN');
        $this->assertType('EmpDto', $rows[0]);
        $this->assertEquals($rows[0]->getEmpno(),7499);

        $rows = $dao->findEmpByEnameFile('ALLEN');
        $this->assertType('EmpDto', $rows[0]);
        $this->assertEquals($rows[0]->getEmpno(),7499);
        
    }
            
    /**
     * testDaoBean
     * @return 
     */
    public function testDaoBeanArray() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testDaoBean.dicon');
        $dao = $container->getComponent('empDao');
        $rows = $dao->findEmpByEnameArray(array('ALLEN','JAMES'));
        $this->assertEquals($rows[0]->getEmpno(),7499);
        
    }
            
    /**
     * testDaoBean
     * @return 
     */
    public function testDaoBeanObject() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testDaoBean.dicon');
        $dao = $container->getComponent('empDao');
        $obj = new EmpDto();
        $obj->setEname('ALLEN');
        $rows = $dao->findEmpByEnameObject($obj);
        $this->assertEquals($rows[0]->getEmpno(),7499);
        
    }
            
    /**
     * testDaoLogic
     * @return 
     */
    public function testDaoLogic() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testDaoLogic.dicon');
        $logic = $container->getComponent('empLogic');
        $rows = $logic->getEmpByEname('ALLEN');
        $this->assertEquals($rows[0]->getEmpno(),7499);
        
    }

/*
    function testInsertDept() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/mysql/testDao.dicon');
        $dao = $container->getComponent('deptDao');
        $rows = $dao->insertDept(array('99','ZZZ','YYY','0'));
        $this->assertEquals($rows,1);
    }
*/
}
?>