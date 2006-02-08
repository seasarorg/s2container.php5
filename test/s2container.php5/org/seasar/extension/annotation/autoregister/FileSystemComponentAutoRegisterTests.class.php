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

        $this->assertEqual($register->getClassPatternSize(),0);
        $register->addClassPattern(dirname(__FILE__));
        $this->assertEqual($register->getClassPatternSize(),1);
        
        print "\n";
    }    

    function testDirException() {
        print __METHOD__ . "\n";

        $register = new S2Container_FileSystemComponentAutoRegister();

        try{
            $register->addClassPattern('dddd');
            $this->assertTrue(false);
        }catch(Exception $e){
            $this->assertTrue(true);
            print "{$e->getMessage()}\n";
        }
        
        print "\n";
    }    

    function testIsIgnore() {
        print __METHOD__ . "\n";

        $register = new Test_FileSystemComponentAutoRegisterTests();
        $register->addIgnoreClassPattern("Foo,Bar");
        $this->assertEqual($register->isIgnore('Foo'),true);
        $this->assertEqual($register->isIgnore('Bar'),true);
        $this->assertEqual($register->isIgnore('Hoge'),false);
        print "\n";
    }
}

class Test_FileSystemComponentAutoRegisterTests
    extends S2Container_FileSystemComponentAutoRegister{

    public function isIgnore($name){
        return parent::isIgnore($name);    
    }
}
?>
