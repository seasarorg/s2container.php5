<?php
class UuCallAopProxyExtensionTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetInterfaces() {
       
        print __METHOD__ . "\n";
       
        $ref = new ReflectionClass(new WextendAW());
        $pref = $ref->getParentClass();
        $met =  $pref->getMethod('awm1');
        $this->assertTrue($met->isAbstract());
       
        print "\n";
    }

    function testAopProxyFactory() {
       
        print __METHOD__ . "\n";
       
        $c = UuCallAopProxyFactory::create(new ReflectionClass(new WextendAW()),array(),array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
              
        $c = UuCallAopProxyFactory::create(new ReflectionClass('IW'),array(),array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
          
        print "\n";
    }

    function testAopProxyFactory2() {
       
        print __METHOD__ . "\n";
       
        $c = UuCallAopProxyFactory::create(new ReflectionClass(new WextendAW()),array(),array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
              
        $c = UuCallAopProxyFactory::create(new ReflectionClass(new WextendAW()),array(),array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
          
        print "\n";
    }
   
}
?>
