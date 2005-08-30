<?php
class ManualPropertyAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testValue() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('E','e');

        $ecd = $container->getComponentDef('e');
        $ecd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new PropertyDefImpl('name','test-test');
        $ecd->addPropertyDef($pro);

          
        $e = $container->getComponent('e');
        $this->assertEqual($e->getName(),"test-test");

        print "\n";
    }

    function testChild() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('E','e');

        $dcd = $container->getComponentDef('d');
        $ecd = $container->getComponentDef('e');
        $ecd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new PropertyDefImpl('d',null);
        $pro->setChildComponentDef($dcd);
        $ecd->addPropertyDef($pro);

          
        $d = $container->getComponent('d');
        $e = $container->getComponent('e');
        $ed = $e->getD();
        $this->assertReference($ed,$d);

        print "\n";
    }
   
    function testExp() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('E','e');

        $ecd = $container->getComponentDef('e');
        $ecd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new PropertyDefImpl('name',null);
        $pro->setExpression("1+1");
        $ecd->addPropertyDef($pro);

          
        $e = $container->getComponent('e');
        $this->assertEqual($e->getName(),2);

        print "\n";
    }   
}
?>