<?php
class SessionComponentDeployerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testSession1() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('L','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_SESSION);
          
        $l = $container->getComponent('l');
        $this->assertIsA($l->getComp(),'D');

        $ll = $container->getComponent('l');
        $this->assertReference($l,$ll);
          
        print "\n";
    }
   
    function testSession2() {
       
        print __METHOD__ . "\n";
       
        $_SESSION['l'] = "test string";
          
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('L','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_SESSION);
          
        $l = $container->getComponent('l');
        $this->assertIsA($l,'L');

        print "\n";
    }
}
?>