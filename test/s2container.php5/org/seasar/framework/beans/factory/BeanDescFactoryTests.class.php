<?php
class BeanDescFactoryTests extends UnitTestCase {
   function __construct() {
       $this->UnitTestCase();
   }

   function testGetBeanDesc() {
       
       print __METHOD__ . "\n";       
    
       $a = new ReflectionClass('A');
       $desc = BeanDescFactory::getBeanDesc($a);
       
       $desc = BeanDescFactory::getBeanDesc($a);

       print "\n";
   }
}
?>
