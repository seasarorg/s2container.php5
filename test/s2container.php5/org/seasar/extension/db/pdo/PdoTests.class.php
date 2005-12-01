<?php
class PdoTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testDataSource() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(S2CONTAINER_PHP5 . '/org/seasar/extension/db/pdo.dicon');
        $this->assertNotNull($container);
        $ds = $container->getComponent('pdo.dataSource');
        $this->assertNotNull($ds);

        print("Data Source : ");
        print($ds);
        print "\n";
        $db = $ds->getConnection();
        $result = $db->query('select * from dept where deptno = 10;'); 
        $rows = $result->fetch(PDO::FETCH_BOUND);
        $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));
        
        $db = null;
        print "\n";
    }

    function testComponent() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/pdo/testPdo.dicon');
        $dao = $container->getComponent('deptDao');
        $this->assertNotNull($dao);
        
        $rows = $dao->findDeptByDeptno(10);
        $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));

        print "\n";
    }

    function testTxAspect() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/pdo/testTx.dicon');

        $dao = $container->getComponent('deptDao');
        $this->assertNotNull($dao);
        $rows = $dao->findDeptByName('ACCOUNTING');
        $this->assertEqual($rows,array('10','ACCOUNTING','NEW YORK','0'));

        print "\n";
    }

    function testTxAspectUpdate() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/pdo/testTx.dicon');

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

}
?>