<?php
class CommentAnnotationHandlerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testCreateComponentDef() {
        print __METHOD__ . "\n";
      
        $handler = new S2Container_CommentAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('A_CommentAnnotationHandlerTests'),
                                  "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');
        $this->assertEqual($cd->getInstanceMode(),'singleton');

        $cd = $handler->createComponentDef(
              new ReflectionClass('B_CommentAnnotationHandlerTests'),
                                  "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');
        $this->assertEqual($cd->getComponentName(),'a');
        $this->assertEqual($cd->getInstanceMode(),'prototype');
        $this->assertEqual($cd->getAutoBindingMode(),'none');
      
        print "\n";
    }

    function testAppendDI() {
        print __METHOD__ . "\n";
      
        $handler = new S2Container_CommentAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('B_CommentAnnotationHandlerTests'),
                                  "singleton");
      
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');

        $handler->appendDI($cd);
        $obj = $cd->getComponent();
        $this->assertEqual($obj->getFoo(),'test');
        print "\n";
    }    

    function testAppendAspect() {
        print __METHOD__ . "\n";
      
        $handler = new S2Container_CommentAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('C_CommentAnnotationHandlerTests'),
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

class A_CommentAnnotationHandlerTests{}

/**
 * @S2Container_ComponentAnnotation(name = 'a',
 *                                  instance = 'prototype',
 *                                  autoBinding = 'none')
 */
class B_CommentAnnotationHandlerTests{

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

/**
 * @S2Container_ComponentAnnotation(name = 'c')
 * 
 * test annotation
 * @S2Container_AspectAnnotation(interceptor = TestInterceptor,
 *                               pointcut = foo bar)
 */
class C_CommentAnnotationHandlerTests{

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
    public function setFoo($foo){
        $this->foo = $foo;    
    }
    
    public function getFoo(){
        return $this->foo;   
    }

    public function getBar(){
        return $this->bar;   
    }
    
    /**
     * @S2Container_InitMethodAnnotation
     */
    public function initTest(){
        print "init method called.\n";
    }

}

class D_CommentAnnotationHandlerTests{

    /**
     * @S2Container_InitMethodAnnotation
     */
    public function initExceptionTest($arg1){
        print "init method called.\n";
    }
}

class E_CommentAnnotationHandlerTests{

    /**
     * @S2Container_InitMethodAnnotation
     */
    public function initExceptionTest(){
        print "init method called.\n";
    }
}
?>
