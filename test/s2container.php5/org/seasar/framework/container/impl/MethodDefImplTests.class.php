<?php
class MethodDefImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testArgDef() {
       
        print __METHOD__ . "\n";
       
        $arg1 = new S2Container_ArgDefImpl('a');
        $arg2 = new S2Container_ArgDefImpl('b');
        $arg3 = new S2Container_ArgDefImpl('c');

        $im = new S2Container_InitMethodDefImpl('hoge');
        $im->addArgDef($arg1);
        $im->addArgDef($arg2);
        $im->addArgDef($arg3);

        $this->assertEqual($im->getArgDefSize(),3);

        $arg = $im->getArgDef(1);
        $this->assertReference($arg,$arg2);

        $args = $im->getArgs();
        $this->assertEqual($args,array('a','b','c'));
        
        print "\n";
    } 
}
?>