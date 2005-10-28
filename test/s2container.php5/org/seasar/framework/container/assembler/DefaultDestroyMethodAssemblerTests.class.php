<?php
class DefaultDestroyMethodAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testDestroy() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('G','g');

        $cd = $container->getComponentDef('g');
        $me = new S2Container_DestroyMethodDefImpl('finish');
        $cd->addDestroyMethodDef($me);

        $me = new S2Container_DestroyMethodDefImpl('finish2');
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("destroy test.");
        $me->addArgDef($arg);
        $cd->addDestroyMethodDef($me);

        $g = $container->init();
          
        $container->destroy();

        print "\n";
    }
}
?>