<?php
class PrototypeComponentDeployerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testProto1() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('C','c');
          
        $cd = $container->getComponentDef('c');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_PROTOTYPE);
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("test-test");
        $cd->addArgDef($arg);
          
        $c = $container->getComponent('c');
        $this->assertEqual($c->say(),'test-test');

        print "\n";
    }

    function testProto2() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('C','c');
          
        $cd = $container->getComponentDef('c');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_PROTOTYPE);
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("test-test");
        $cd->addArgDef($arg);
          
        $c = $container->getComponent('c');
        $cc = $container->getComponent('c');

        $this->assertTrue($c !== $cc);

        print "\n";
    }
}
?>