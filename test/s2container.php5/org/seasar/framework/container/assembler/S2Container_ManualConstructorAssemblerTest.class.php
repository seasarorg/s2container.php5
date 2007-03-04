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
class S2Container_ManualConstructorAssemblerTest
    extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testArgValue() {
        $container = new S2ContainerImpl();
        $container->register('C_S2Container_ManualConstructorAssembler','c');
          
        $cd = $container->getComponentDef('c');
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("test-test");
        $cd->addArgDef($arg);
          
        $c = $container->getComponent('c');
        $this->assertEquals($c->say(),'test-test');
    }

    function testArgExp() {
        $container = new S2ContainerImpl();
        $container->register('C_S2Container_ManualConstructorAssembler','c');
        $cd = $container->getComponentDef('c');
        $arg = new S2Container_ArgDefImpl();
          
        //$arg->setExpression("return 1+1;");
        //$arg->setExpression("1+1;");
        //$arg->setExpression(" 1 + 1  ");
        //$arg->setExpression(' function(){return 2;}; '); // ERROR!!
        //$arg->setExpression(" return 1 + 1  ");
        //$arg->setExpression(" '2' ");
        $arg->setExpression(" 1 + 1 ;\n return 1+3; ");

        $cd->addArgDef($arg);
        $c = $container->getComponent('c');
        $this->assertEquals($c->say(),4);
    }

    function testArgNone() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_ManualConstructorAssembler','d');
          
        $dcd = $container->getComponentDef('d');
        $d = $container->getComponent('d');
        $this->assertNotNull($d);
    }

    function testArgChildComponentDef() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_ManualConstructorAssembler','d');
        $container->register('C_S2Container_ManualConstructorAssembler','c');
          
        $dcd = $container->getComponentDef('d');
        $cd = $container->getComponentDef('c');
        $arg = new S2Container_ArgDefImpl();
        $arg->setChildComponentDef($dcd);
        $cd->addArgDef($arg);

        $c = $container->getComponent('c');
        $d = $container->getComponent('d');
        $this->assertEquals($c->say(),$d);
    }
}

class C_S2Container_ManualConstructorAssembler {
    private $name;
    function __construct($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}

interface IG_S2Container_ManualConstructorAssembler {}
class D_S2Container_ManualConstructorAssembler 
    implements IG_S2Container_ManualConstructorAssembler {}

?>
