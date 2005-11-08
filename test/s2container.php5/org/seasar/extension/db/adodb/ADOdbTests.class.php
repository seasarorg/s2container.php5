<?php
class ADOdbTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testDataSource() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(S2CONTAINER_PHP5 . '/org/seasar/extension/db/adodb.dicon');
        $this->assertNotNull($container);
        $ds = $container->getComponent('adodb.dataSource');
        $this->assertNotNull($ds);

        print("Data Source : ");
        print($ds);
        print "\n";
        $db = $ds->getConnection();
        $db->SetFetchMode(ADODB_FETCH_NUM);
        $result = $db->query('select * from dept where deptno = 10;'); 
        $rows = $result->fetchRow();
        $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));

        if($ds->isCache()){
            $result = $db->CacheExecute($ds->getCacheSecs(),'select * from dept where deptno = 10;'); 
            $rows = $result->fetchRow();
            $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));
        }
        
        $db->disconnect();
        
        print "\n";
    }

    function testComponent() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testADOdb.dicon');
        $dao = $container->getComponent('deptDao');
        $this->assertNotNull($dao);
        
        $rows = $dao->findDeptByDeptno(10);
        $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));

        print "\n";
    }

    function testTxAspect() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testTx.dicon');

        $dao = $container->getComponent('deptDao');
        $this->assertNotNull($dao);
        $rows = $dao->findDeptByName('ACCOUNTING');
        $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));

        print "\n";
    }

    function testTxAspectUpdate() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testTx.dicon');

        $dao = $container->getComponent('deptDao');
        $this->assertNotNull($dao);
        try{
            $ret = $dao->updateDnameByDeptno('40','SALES');
            $this->assertEqual($ret,null);
        }catch(Exception $e){
            print "Exception catched.\n";
        }

        print "\n";
    }

/* */
    function testDao() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testDao.dicon');
        $dao = $container->getComponent('deptDao');
        $rows = $dao->findDeptByName('ACCOUNTING');
        $this->assertEqual($rows[0],array('10','ACCOUNTING','NEW YORK','0'));
        print "\n";
    }

    function testDaoBean() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testDaoBean.dicon');
        $dao = $container->getComponent('empDao');
        $rows = $dao->findEmpByEname('ALLEN');
        $this->assertIsA($rows[0],'EmpDto');
        $this->assertEqual($rows[0]->getEmpno(),7499);

        $rows = $dao->findEmpByEnameQuery('ALLEN');
        $this->assertIsA($rows[0],'EmpDto');
        $this->assertEqual($rows[0]->getEmpno(),7499);

        $rows = $dao->findEmpByEnameFile('ALLEN');
        $this->assertIsA($rows[0],'EmpDto');
        $this->assertEqual($rows[0]->getEmpno(),7499);

        print "\n";
        
    }

    function testDaoBeanArray() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testDaoBean.dicon');
        $dao = $container->getComponent('empDao');
        $rows = $dao->findEmpByEnameArray(array('ALLEN','JAMES'));
        $this->assertEqual($rows[0]->getEmpno(),7499);
        print "\n";
        
    }

    function testDaoBeanObject() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testDaoBean.dicon');
        $dao = $container->getComponent('empDao');
        $obj = new EmpDto();
        $obj->setEname('ALLEN');
        $rows = $dao->findEmpByEnameObject($obj);
        $this->assertEqual($rows[0]->getEmpno(),7499);
        print "\n";
        
    }

    function testDaoLogic() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testDaoLogic.dicon');
        $logic = $container->getComponent('empLogic');
        $rows = $logic->getEmpByEname('ALLEN');
        $this->assertEqual($rows[0]->getEmpno(),7499);
        print "\n";
        
    }

/*
    function testInsertDept() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/adodb/testDao.dicon');
        $dao = $container->getComponent('deptDao');
        $rows = $dao->insertDept(array('99','ZZZ','YYY','0'));
        $this->assertEqual($rows,1);
        print "\n";
    }
*/
}
?>