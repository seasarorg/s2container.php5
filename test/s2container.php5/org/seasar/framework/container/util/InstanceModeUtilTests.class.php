<?php
class InstanceModeUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    private $a = array();
    function testArray() {
       
        print __METHOD__ . "\n";
       
        $this->a[0] = new A();
        $this->a[1] = new B();
        $this->a[2] = new A();
        $this->a[3] = new B();
       
        $j = 0;
        foreach($this->a as $i){
            $k = $this->arrayTest($j);
            $this->assertReference($k,$i);
            $j++;
        }
          
        print "\n";
    }
   
    function arrayTest($index){
        return $this->a[$index];
    }
   
    function testIsOuter() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(S2Container_InstanceModeUtil::isOuter('outer'));
        $this->assertTrue(S2Container_InstanceModeUtil::isOuter('oUter'));
        $this->assertFalse(S2Container_InstanceModeUtil::isOuter('oouter'));

        print "\n";
    }

    function testIsSingleton() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(S2Container_InstanceModeUtil::isSingleton('singleton'));
        $this->assertTrue(S2Container_InstanceModeUtil::isSingleton('SingleTon'));
        $this->assertFalse(S2Container_InstanceModeUtil::isSingleton('single'));

        print "\n";
    }

    function testIsPrototype() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(S2Container_InstanceModeUtil::isPrototype('prototype'));
        $this->assertTrue(S2Container_InstanceModeUtil::isPrototype('Prototype'));
        $this->assertFalse(S2Container_InstanceModeUtil::isPrototype('pro'));

        print "\n";
    }    

    function testIsRequest() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(S2Container_InstanceModeUtil::isRequest('request'));
        $this->assertTrue(S2Container_InstanceModeUtil::isRequest('Request'));
        $this->assertFalse(S2Container_InstanceModeUtil::isRequest('req'));

        print "\n";
    }    

    function testIsSession() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(S2Container_InstanceModeUtil::isSession('session'));
        $this->assertTrue(S2Container_InstanceModeUtil::isSession('Session'));
        $this->assertFalse(S2Container_InstanceModeUtil::isSession('ses'));

        print "\n";
    }    
}
?>