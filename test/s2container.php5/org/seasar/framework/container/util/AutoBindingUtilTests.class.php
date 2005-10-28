<?php
class AutoBindingUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testMode() {
       
        print __METHOD__ . "\n";

        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('auto'));
        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('AUTO'));
        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('auTo'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isAuto('au'));

        $this->assertTrue(S2Container_AutoBindingUtil::isConstructor('constructor'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isConstructor('con'));

        $this->assertTrue(S2Container_AutoBindingUtil::isProperty('property'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isProperty('prop'));

        $this->assertTrue(S2Container_AutoBindingUtil::isNone('none'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isNone('no'));
          
        print "\n";
    }

    function testIsSuitable() {
       
        print __METHOD__ . "\n";

        $this->assertTrue(S2Container_AutoBindingUtil::isSuitable(new ReflectionClass('IA')));
        $this->assertTrue(!S2Container_AutoBindingUtil::isSuitable(new ReflectionClass('A')));

        $res = array(new ReflectionClass('IA'),
                      new ReflectionClass('IB'));
        $this->assertTrue(S2Container_AutoBindingUtil::isSuitable($res));

        $res = array(new ReflectionClass('A'),
                      new ReflectionClass('IB'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isSuitable($res));
        
        print "\n";
    }
}
?>