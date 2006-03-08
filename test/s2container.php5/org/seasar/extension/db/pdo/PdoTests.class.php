<?php
class PdoTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testDataSource() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/extension/db/pdo/pdo.dicon');
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
}
?>