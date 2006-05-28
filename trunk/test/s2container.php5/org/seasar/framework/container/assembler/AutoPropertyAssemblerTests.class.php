<?php

class AutoPropertyAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testChild() {
       
        print __METHOD__ . "\n";       
       
        $container = new S2ContainerImpl();
        $container->register('G','g');
        $container->register('H','h');

        $g = $container->getComponent('g');
        $h = $container->getComponent('h');
        $hg = $h->getG();
        $this->assertReference($hg,$g);

        print "\n";
    }

    function testValue() {
       
        print __METHOD__ . "\n";       
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('E','e');

        $ecd = $container->getComponentDef('e');
        $pro = new S2Container_PropertyDefImpl('name','test-test');
        $ecd->addPropertyDef($pro);

          
        $e = $container->getComponent('e');
        $this->assertEqual($e->getName(),"test-test");

        print "\n";
    }

    function testNoComponent() {
       
        print __METHOD__ . "\n";       
       
        $container = new S2ContainerImpl();
        $container->register('L','l');

        $l = $container->getComponent('l');
        $this->assertIsA($l,'L');

        print "\n";
    }
}
?>