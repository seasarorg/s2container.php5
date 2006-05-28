<?php
class PropertyDescTests extends UnitTestCase {
   function __construct() {
       $this->UnitTestCase();
   }

   function testToString() {
       
       print __METHOD__ . "\n";       
    
       $a = new ReflectionClass('N');
       $desc = new S2Container_BeanDescImpl($a);
       $propDesc = $desc->getPropertyDesc('val1');
       $this->assertIsA($propDesc,'S2Container_PropertyDescImpl');

       print($propDesc);
       print "\n";
       $this->assertEqual($propDesc->__toString(),
                          "propertyName=val1,propertyType=null,readMethod=getVal1,writeMethod=null");

       print "\n";
   }
   
   function testRWMethod() {
       
       print __METHOD__ . "\n";       
    
       $a = new ReflectionClass('N');
       $desc = new S2Container_BeanDescImpl($a);
       $propDesc = $desc->getPropertyDesc('val1');
       $this->assertIsA($propDesc,'S2Container_PropertyDescImpl');

       $propDesc = $desc->getPropertyDesc('val2');
       $m = $propDesc->getWriteMethod();
       $this->assertEqual($m->getName(),"setVal2");
       $m = $propDesc->getReadMethod();
       $this->assertEqual($m->getName(),"getVal2");

       print "\n";
   }   
}
?>