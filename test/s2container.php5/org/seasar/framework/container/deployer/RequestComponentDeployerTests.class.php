<?php
class RequestComponentDeployerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testRequest1() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('L','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(ContainerConstants::INSTANCE_REQUEST);
          
        $l = $container->getComponent('l');
        $this->assertIsA($l->getComp(),'D');

        $ll = $container->getComponent('l');
        $this->assertReference($l,$ll);
          
        print "\n";
    }
   
    function testRequest2() {
       
        print __METHOD__ . "\n";
       
        $_REQUEST['l'] = "test string";
          
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('L','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(ContainerConstants::INSTANCE_REQUEST);
          
        $l = $container->getComponent('l');
        $this->assertIsA($l,'L');

        print "\n";
    }

}
?>