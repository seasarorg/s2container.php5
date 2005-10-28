<?php
class EvalUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetExpression() {
       
        print __METHOD__ . "\n";
       
        $exp = "100";
        $ret = "return 100;";
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEqual($ret,$result);

        $exp = '"hoge"';
        $ret = 'return "hoge";';
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEqual($ret,$result);

        $exp = 'return "hoge"';
        $ret = 'return "hoge";';
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEqual($ret,$result);

        $exp = 'return 1000';
        $ret = 'return 1000;';
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEqual($ret,$result);

        $exp = '1000;';
        $ret = 'return 1000;';
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEqual($ret,$result);
       
        print "\n";
    }

    function testAddSemiColon() {
       
        print __METHOD__ . "\n";

        $exp = '1000';
        $ret = '1000;';
        $result = S2Container_EvalUtil::addSemiColon($exp);
        $this->assertEqual($ret,$result);

        $exp = '1000;';
        $ret = '1000;';
        $result = S2Container_EvalUtil::addSemiColon($exp);
        $this->assertEqual($ret,$result);

        print "\n";
    }
}
?>