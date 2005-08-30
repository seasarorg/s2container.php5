<?php
class ManualConstructorAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testArgValue() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('C','c');
          
        $cd = $container->getComponentDef('c');
        $arg = new ArgDefImpl();
        $arg->setValue("test-test");
        $cd->addArgDef($arg);
          
        $c = $container->getComponent('c');
        $this->assertEqual($c->say(),'test-test');

        print "\n";
    }

    function testArgExp() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('C','c');
        $cd = $container->getComponentDef('c');
        $arg = new ArgDefImpl();
          
        //$arg->setExpression("return 1+1;");
        //$arg->setExpression("1+1;");
        //$arg->setExpression(" 1 + 1  ");
        //$arg->setExpression(' function(){return 2;}; '); // ERROR!!
        //$arg->setExpression(" return 1 + 1  ");
        //$arg->setExpression(" '2' ");
        $arg->setExpression(" 1 + 1 ;\n return 1+3; ");

        $cd->addArgDef($arg);
        $c = $container->getComponent('c');
        $this->assertEqual($c->say(),4);

        print "\n";
    }

    function testArgNone() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
          
        $dcd = $container->getComponentDef('d');
        $d = $container->getComponent('d');
        $this->assertNotNull($d);

        print "\n";
    }

    function testArgChildComponentDef() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('C','c');
          
        $dcd = $container->getComponentDef('d');
        $cd = $container->getComponentDef('c');
        $arg = new ArgDefImpl();
        $arg->setChildComponentDef($dcd);
        $cd->addArgDef($arg);

        $c = $container->getComponent('c');
        $d = $container->getComponent('d');
        $this->assertEqual($c->say(),$d);

        print "\n";
    }
}
?>