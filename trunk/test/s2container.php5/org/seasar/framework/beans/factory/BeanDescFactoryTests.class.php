<?php
class BeanDescFactoryTests extends UnitTestCase {
   function __construct() {
       $this->UnitTestCase();
   }

   function testGetBeanDesc() {
       
       print __METHOD__ . "\n";       
    
       $a = new ReflectionClass('A');
       $desc = S2Container_BeanDescFactory::getBeanDesc($a);
       
       $desc = S2Container_BeanDescFactory::getBeanDesc($a);

       print "\n";
   }
}
?>
