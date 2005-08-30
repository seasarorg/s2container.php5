<?php
class AutoBindingUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testMode() {
       
        print __METHOD__ . "\n";

        $this->assertTrue(AutoBindingUtil::isAuto('auto'));
        $this->assertTrue(AutoBindingUtil::isAuto('AUTO'));
        $this->assertTrue(AutoBindingUtil::isAuto('auTo'));
        $this->assertTrue(!AutoBindingUtil::isAuto('au'));

        $this->assertTrue(AutoBindingUtil::isConstructor('constructor'));
        $this->assertTrue(!AutoBindingUtil::isConstructor('con'));

        $this->assertTrue(AutoBindingUtil::isProperty('property'));
        $this->assertTrue(!AutoBindingUtil::isProperty('prop'));

        $this->assertTrue(AutoBindingUtil::isNone('none'));
        $this->assertTrue(!AutoBindingUtil::isNone('no'));
          
        print "\n";
    }

    function testIsSuitable() {
       
        print __METHOD__ . "\n";

        $this->assertTrue(AutoBindingUtil::isSuitable(new ReflectionClass('IA')));
        $this->assertTrue(!AutoBindingUtil::isSuitable(new ReflectionClass('A')));

        $res = array(new ReflectionClass('IA'),
                      new ReflectionClass('IB'));
        $this->assertTrue(AutoBindingUtil::isSuitable($res));

        $res = array(new ReflectionClass('A'),
                      new ReflectionClass('IB'));
        $this->assertTrue(!AutoBindingUtil::isSuitable($res));
        
        print "\n";
    }
}
?>