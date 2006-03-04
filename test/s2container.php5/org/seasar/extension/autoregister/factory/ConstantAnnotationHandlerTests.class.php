<?php
class ConstantAnnotationHandlerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testCreateComponentDef() {
        print __METHOD__ . "\n";
      
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('A_ConstantAnnotationHandlerTests'),
                                  "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');
        $this->assertEqual($cd->getInstanceMode(),'singleton');

        $cd = $handler->createComponentDef(
              new ReflectionClass('B_ConstantAnnotationHandlerTests'),
                                  "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');
        $this->assertEqual($cd->getComponentName(),'a');
        $this->assertEqual($cd->getInstanceMode(),'prototype');
        $this->assertEqual($cd->getAutoBindingMode(),'none');
      
        try{
            
            $cd = $handler->createComponentDef(
                  new ReflectionClass('BERR_ConstantAnnotationHandlerTests'),
                                      "singleton");
            $this->assertTrue(false);
        }catch(Exception $e){
            $this->assertTrue(true);
            print "{$e->getMessage()}\n";
        }
        print "\n";
    }

    function testAppendDI() {
        print __METHOD__ . "\n";
      
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('B_ConstantAnnotationHandlerTests'),
                                  "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');

        $handler->appendDI($cd);
        $obj = $cd->getComponent();
        $this->assertEqual($obj->getFoo(),'test');
        print "\n";
    }    

    function testAppendAspect() {
        print __METHOD__ . "\n";
      
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('C_ConstantAnnotationHandlerTests'),
                                  "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');

        $handler->appendAspect($cd);

        $this->assertEqual($cd->getAspectDef(0)->getExpression(),
                           "TestInterceptor");

        print "\n";
    }    

    function testAppendInitMethod() {
        print __METHOD__ . "\n";
      
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('C_ConstantAnnotationHandlerTests'),
                                  "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');

        $handler->appendInitMethod($cd);

        $this->assertEqual($cd->getInitMethodDef(0)->getMethodName(),
                           "initTest");

        print "\n";
    }
    
    function testInitMethodArgException() {
        print __METHOD__ . "\n";
      
        try{
            $handler = new S2Container_ConstantAnnotationHandler();
            $cd = $handler->createComponentDef(
                  new ReflectionClass('D_ConstantAnnotationHandlerTests'),
                                      "singleton");
      
            $this->assertIsA($cd,'S2Container_ComponentDefImpl');
            $handler->appendInitMethod($cd);

            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }

        print "\n";
    }    

    function testInitMethodRedundantIgnored() {
        print __METHOD__ . "\n";
      
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
                  new ReflectionClass('E_ConstantAnnotationHandlerTests'),
                                      "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');
        $cd->addInitMethodDef(
                new S2Container_InitMethodDefImpl('initExceptionTest'));
        $this->assertEqual($cd->getInitMethodDefSize(),1);
          
        $handler->appendInitMethod($cd);
        $this->assertEqual($cd->getInitMethodDefSize(),1);

        print "\n";
    }    
}

class A_ConstantAnnotationHandlerTests{}

class BERR_ConstantAnnotationHandlerTests{
    const COMPONENT = "name = =b, 
                       instance = prototype, 
                       autoBinding = none";                  
}

class B_ConstantAnnotationHandlerTests{
    const COMPONENT = "name = a, 
                       instance = prototype, 
                       autoBinding = none";
    private $foo;
    private $bar;
    private $hoge;
    
    public function setHoge($hoge){
        $this->hoge = $hoge;
    }

    public function setBar($bar){
        $this->bar = $bar;
    }
    
    /**
     * @S2Container_BindingAnnotation('"test"')
     */
    const foo_BINDING = '"test"';
    public function setFoo($foo){
        $this->foo = $foo;    
    }
    
    public function getFoo(){
        return $this->foo;   
    }

    public function getBar(){
        return $this->bar;   
    }
}

class C_ConstantAnnotationHandlerTests{
    const COMPONENT = "name = c";
    const ASPECT = "interceptor = TestInterceptor,
                    pointcut = foo bar";
    private $foo;
    private $bar;
    private $hoge;
    
    public function setHoge($hoge){
        $this->hoge = $hoge;
    }

    public function setBar($bar){
        $this->bar = $bar;
    }
    
    public function setFoo($foo){
        $this->foo = $foo;    
    }
    
    public function getFoo(){
        return $this->foo;   
    }

    public function getBar(){
        return $this->bar;   
    }

    const INIT_METHOD = "initTest";    
    public function initTest(){
        print "init method called.\n";
    }

}

class D_ConstantAnnotationHandlerTests{

    const INIT_METHOD = "initExceptionTest";    
    public function initExceptionTest($arg1){
        print "init method called.\n";
    }
}

class E_ConstantAnnotationHandlerTests{

    const INIT_METHOD = "initExceptionTest";    
    public function initExceptionTest(){
        print "init method called.\n";
    }
}
?>
