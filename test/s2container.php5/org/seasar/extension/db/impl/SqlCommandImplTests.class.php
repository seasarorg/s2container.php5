<?php
class SqlCommandImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testConstructSQL() {
       
        print __METHOD__ . "\n";

        $sql = 'select $a from $b where col2 = \'$c\';';
        $argNames = array('a','b','c');
        $args = array('col1','table1','10');
        $ret = 'select col1 from table1 where col2 = \'10\';';
        $result = SqlCommandImpl::constructSql($sql,$argNames,$args);
        $this->assertEqual($ret,$result);

        print "\n";
    }
}
?>