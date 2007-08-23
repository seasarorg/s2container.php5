<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * @package org.seasar.framework.extension.autoregister.factory
 * @author klove
 */
class S2Conatiner_ConstantAnnotationHandlerTest
    extends PHPUnit_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testCreateComponentDef() {
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('A_ConstantAnnotationHandler'),
                                  "singleton");
      
        $this->assertType('S2Container_ComponentDefImpl',$cd);
        $this->assertEquals($cd->getInstanceMode(),'singleton');

        $cd = $handler->createComponentDef(
              new ReflectionClass('B_ConstantAnnotationHandler'),
                                  "singleton");
      
        $this->assertType('S2Container_ComponentDefImpl',$cd);
        $this->assertEquals($cd->getComponentName(),'a');
        $this->assertEquals($cd->getInstanceMode(),'prototype');
        $this->assertEquals($cd->getAutoBindingMode(),'none');
      
        try{
            $cd = $handler->createComponentDef(
                  new ReflectionClass('BERR_ConstantAnnotationHandler'),
                                      "singleton");
            $this->assertTrue(false);
        }catch(Exception $e){
            $this->assertTrue(true);
            print "{$e->getMessage()}\n";
        }
    }

    function testAppendDI() {
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('B_ConstantAnnotationHandler'),
                                  "singleton");
      
        $this->assertType('S2Container_ComponentDefImpl',$cd);

        $handler->appendDI($cd);
        $obj = $cd->getComponent();
        $this->assertEquals($obj->getFoo(),'test');
    }    

    function testAppendAspect() {
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('C_ConstantAnnotationHandler'),
                                  "singleton");
      
        $this->assertType('S2Container_ComponentDefImpl',$cd);

        $handler->appendAspect($cd);

        $this->assertEquals($cd->getAspectDef(0)->getExpression(),
                           "TestInterceptor");
    }    

    function testAppendInitMethod() {
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
              new ReflectionClass('C_ConstantAnnotationHandler'),
                                  "singleton");
      
        $this->assertType('S2Container_ComponentDefImpl',$cd);

        $handler->appendInitMethod($cd);

        $this->assertEquals($cd->getInitMethodDef(0)->getMethodName(),
                           "initTest");
    }
    
    function testInitMethodArgException() {
        try{
            $handler = new S2Container_ConstantAnnotationHandler();
            $cd = $handler->createComponentDef(
                  new ReflectionClass('D_ConstantAnnotationHandler'),
                                      "singleton");
      
            $this->assertType('S2Container_ComponentDefImpl',$cd);
            $handler->appendInitMethod($cd);

            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }
    }    

    function testInitMethodRedundantIgnored() {
        $handler = new S2Container_ConstantAnnotationHandler();
        $cd = $handler->createComponentDef(
                  new ReflectionClass('E_ConstantAnnotationHandler'),
                                      "singleton");
      
        $this->assertType('S2Container_ComponentDefImpl',$cd);
        $cd->addInitMethodDef(
                new S2Container_InitMethodDefImpl('initExceptionTest'));
        $this->assertEquals($cd->getInitMethodDefSize(),1);
          
        $handler->appendInitMethod($cd);
        $this->assertEquals($cd->getInitMethodDefSize(),1);
    }    
}

class A_ConstantAnnotationHandler{}

class BERR_ConstantAnnotationHandler{
    const COMPONENT = "name = =b, 
                       instance = prototype, 
                       autoBinding = none";                  
}

class B_ConstantAnnotationHandler{
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

class C_ConstantAnnotationHandler{
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

class D_ConstantAnnotationHandler{

    const INIT_METHOD = "initExceptionTest";    
    public function initExceptionTest($arg1){
        print "init method called.\n";
    }
}

class E_ConstantAnnotationHandler{

    const INIT_METHOD = "initExceptionTest";    
    public function initExceptionTest(){
        print "init method called.\n";
    }
}
?>
