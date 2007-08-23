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
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
class S2Container_ExpressionConstructorAssemblerTest
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

    function testExp() {
        $cd = new S2Container_ComponentDefImpl('','dd');
        $cd->setExpression("return new D_S2Container_ExpressionConstructorAssembler();");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
          
        $d = $container->getComponent('dd');
        $this->assertType('D_S2Container_ExpressionConstructorAssembler',$d);
    }   

    function testGetComponent() {
        $cd = new S2Container_ComponentDefImpl('','dd');
        $cd->setExpression("d");
        
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_ExpressionConstructorAssembler','d');
        $container->register($cd);
          
        $d = $container->getComponent('d');
        $this->assertType('D_S2Container_ExpressionConstructorAssembler',$d);

        $dd = $container->getComponent('dd');
        $this->assertType('D_S2Container_ExpressionConstructorAssembler',$dd);

        $this->assertTrue($d === $dd);
    }   
    
    function testNotObjectException() {
        $cd = new S2Container_ComponentDefImpl('','dd');
        $cd->setExpression("return 100;");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
     
        try{
            $dd = $container->getComponent('dd');
        }catch(Exception $e ){
            $this->assertType('S2Container_S2RuntimeException',$e);
            print $e->getMessage() ."\n";
        }
    }       
    
    function testClassUnmatchException() {
        $cd = new S2Container_ComponentDefImpl('C_S2Container_ExpressionConstructorAssembler','dd');
        $cd->setExpression("return new D_S2Container_ExpressionConstructorAssembler();");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
     
        try{
            $dd = $container->getComponent('dd');
        }catch(Exception $e ){
            $this->assertType('S2Container_ClassUnmatchRuntimeException',$e);
            print $e->getMessage() ."\n";
        }
    }     
}

class C_S2Container_ExpressionConstructorAssembler {
    private $name;
    function __construct($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}

interface IG_S2Container_ExpressionConstructorAssembler {}
class D_S2Container_ExpressionConstructorAssembler 
    implements IG_S2Container_ExpressionConstructorAssembler {}

?>
