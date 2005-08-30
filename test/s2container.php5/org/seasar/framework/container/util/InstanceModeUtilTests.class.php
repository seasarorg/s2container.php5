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
       
        $this->assertTrue(InstanceModeUtil::isOuter('outer'));
        $this->assertTrue(InstanceModeUtil::isOuter('oUter'));
        $this->assertFalse(InstanceModeUtil::isOuter('oouter'));

        print "\n";
    }

    function testIsSingleton() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(InstanceModeUtil::isSingleton('singleton'));
        $this->assertTrue(InstanceModeUtil::isSingleton('SingleTon'));
        $this->assertFalse(InstanceModeUtil::isSingleton('single'));

        print "\n";
    }

    function testIsPrototype() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(InstanceModeUtil::isPrototype('prototype'));
        $this->assertTrue(InstanceModeUtil::isPrototype('Prototype'));
        $this->assertFalse(InstanceModeUtil::isPrototype('pro'));

        print "\n";
    }    

    function testIsRequest() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(InstanceModeUtil::isRequest('request'));
        $this->assertTrue(InstanceModeUtil::isRequest('Request'));
        $this->assertFalse(InstanceModeUtil::isRequest('req'));

        print "\n";
    }    

    function testIsSession() {
       
        print __METHOD__ . "\n";
       
        $this->assertTrue(InstanceModeUtil::isSession('session'));
        $this->assertTrue(InstanceModeUtil::isSession('Session'));
        $this->assertFalse(InstanceModeUtil::isSession('ses'));

        print "\n";
    }    
}
?>