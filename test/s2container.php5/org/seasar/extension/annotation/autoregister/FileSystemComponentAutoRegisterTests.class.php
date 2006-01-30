<?php
class FileSystemComponentAutoRegisterTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $register = new S2Container_FileSystemComponentAutoRegister();
        $this->assertIsA($register,'S2Container_FileSystemComponentAutoRegister');

        print "\n";
    }
    
    function testRegisterAll() {
        print __METHOD__ . "\n";

        $register = new S2Container_FileSystemComponentAutoRegister();
        $register->setAutoNaming(new S2Container_DefaultAutoNaming());
        $container = new S2ContainerImpl();
        $register->setContainer($container);
        $register->addClassPattern(dirname(__FILE__).'/sample');
        $register->registerAll();
        
        $this->assertTrue($container->hasComponentDef('a_FileSystemComponentAutoRegisterTests'));
        $this->assertTrue($container->hasComponentDef('A_FileSystemComponentAutoRegisterTests'));
        $this->assertTrue($container->hasComponentDef('testB'));
        $this->assertTrue($container->hasComponentDef('testC'));
        $this->assertTrue($container->hasComponentDef('interceptor'));
        $c = $container->getComponent('testC');

        $this->assertIsA($c->getA(),'A_FileSystemComponentAutoRegisterTests');
        $this->assertIsA($c->getB(),'B_FileSystemComponentAutoRegisterTests');
        $c->testInterceptor();
        $c->testTrace();
        
        print "\n";
    }    

    function testContainer() {
        print __METHOD__ . "\n";

        $register = new S2Container_FileSystemComponentAutoRegister();

        $container = new S2ContainerImpl();
        $register->setContainer($container);
        $this->assertReference($register->getContainer(),$container);
 
        print "\n";
    }    

    function testClassPatternSize() {
        print __METHOD__ . "\n";

        $register = new S2Container_FileSystemComponentAutoRegister();

        $pat = new S2Container_ClassPattern(dirname(__FILE__),"");
        $this->assertEqual($register->getClassPatternSize(),0);
        $register->addClassPattern($pat);
        $this->assertEqual($register->getClassPatternSize(),1);
        $this->assertReference($register->getClassPattern(0),$pat);
        
        print "\n";
    }    
}
?>
