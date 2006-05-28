<?php
class AutoConstructorAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testAutoValue() {
       
        print __METHOD__ . "\n";       
        $container = new S2ContainerImpl();
        $container->register('C','c');
        $c = $container->getComponent('c');
        $this->assertNull($c->say());

        print "\n";
    }

    function testAutoChild() {
       
        print __METHOD__ . "\n";       
          
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('F','f');
          
        $d = $container->getComponent('d');
        $f = $container->getComponent('f');
        $this->assertEqual($f->getItem(),$d);

        print "\n";
    }
}
?>