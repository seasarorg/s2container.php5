<?php
class UuSetPropertyDescImplTests extends UnitTestCase {
   function __construct() {
       $this->UnitTestCase();
   }

   function testAutoValueUUSet() {
       
       print __METHOD__ . "\n";       
    
       $container = new S2ContainerImpl();
       $container->register('M','m');

       $ecd = $container->getComponentDef('m');
       $ecd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
       $pro = new PropertyDefImpl('name','test-test');
       $ecd->addPropertyDef($pro);
          
       $m = $container->getComponent('m');
       $this->assertEqual($m->getName(),"test-test");

       print "\n";
   }

   function testAutoValueUUSet2() {
       
       print __METHOD__ . "\n";       
    
       $container = new S2ContainerImpl();
       $container->register('M2','m');

       $ecd = $container->getComponentDef('m');
       $ecd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
       $pro = new PropertyDefImpl('val','test-test');
       $ecd->addPropertyDef($pro);
          
       $m = $container->getComponent('m');
       $this->assertEqual($m->getValue(),"test-test");

       print "\n";
   }

   function testAutoValueUUSet3() {
       
       print __METHOD__ . "\n";       
    
       $container = new S2ContainerImpl();
       $container->register('M3','m');

       $ecd = $container->getComponentDef('m');
       $ecd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
       $pro = new PropertyDefImpl('val','test-test');
       $ecd->addPropertyDef($pro);

       try{          
           $m = $container->getComponent('m');
       }catch(Exception $e){
       	   $this->assertIsA($e,'PropertyNotFoundRuntimeException');
       	   print $e->getMessage() . "\n";
       }
       
       print "\n";
   }
}

class M3 {	
}
?>
