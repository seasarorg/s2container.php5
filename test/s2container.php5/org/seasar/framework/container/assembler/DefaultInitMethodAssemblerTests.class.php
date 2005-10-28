<?php
class DefaultInitMethodAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testNoArgs() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('I','i');

        $cd = $container->getComponentDef('i');
        $me = new S2Container_InitMethodDefImpl('culc');
        $cd->addInitMethodDef($me);

          
        $i = $container->getComponent('i');
        $this->assertEqual($i->getResult(),2);

        print "\n";
    }

    function testNoArgsAuto() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('I','i');

        $cd = $container->getComponentDef('i');
        $me = new S2Container_InitMethodDefImpl('culc3');
        $cd->addInitMethodDef($me);
          
        $i = $container->getComponent('i');
        $this->assertEqual($i->getResult(),4);

        print "\n";
    }
   
    function testWithArgs() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('I','i');

        $cd = $container->getComponentDef('i');
        $me = new S2Container_InitMethodDefImpl('culc2');
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("2");
        $me->addArgDef($arg);
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("3");
        $me->addArgDef($arg);
          
        $cd->addInitMethodDef($me);
        $i = $container->getComponent('i');
        $this->assertEqual($i->getResult(),5);

        print "\n";
    }
}
?>