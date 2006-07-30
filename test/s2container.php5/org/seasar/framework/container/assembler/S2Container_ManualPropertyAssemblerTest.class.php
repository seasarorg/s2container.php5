<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
class S2Container_ManualPropertyAssemblerTest
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

    function testValue() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_ManualPropertyAssembler','d');
        $container->register('E_S2Container_ManualPropertyAssembler','e');

        $ecd = $container->getComponentDef('e');
        $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new S2Container_PropertyDefImpl('name','test-test');
        $ecd->addPropertyDef($pro);
          
        $e = $container->getComponent('e');
        $this->assertEquals($e->getName(),"test-test");
    }

    function testChild() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_ManualPropertyAssembler','d');
        $container->register('E_S2Container_ManualPropertyAssembler','e');

        $dcd = $container->getComponentDef('d');
        $ecd = $container->getComponentDef('e');
        $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new S2Container_PropertyDefImpl('d',null);
        $pro->setChildComponentDef($dcd);
        $ecd->addPropertyDef($pro);
          
        $d = $container->getComponent('d');
        $e = $container->getComponent('e');
        $ed = $e->getD();
        $this->assertTrue($ed === $d);
    }
   
    function testExp() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_ManualPropertyAssembler','d');
        $container->register('E_S2Container_ManualPropertyAssembler','e');

        $ecd = $container->getComponentDef('e');
        $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new S2Container_PropertyDefImpl('name',null);
        $pro->setExpression("1+1");
        $ecd->addPropertyDef($pro);
          
        $e = $container->getComponent('e');
        $this->assertEquals($e->getName(),2);
    }   
}

interface IG_S2Container_ManualPropertyAssembler {}
class D_S2Container_ManualPropertyAssembler 
    implements IG_S2Container_ManualPropertyAssembler{}

class E_S2Container_ManualPropertyAssembler {
    private $d;
    private $name;
    function __construct(IG_S2Container_ManualPropertyAssembler $d) {
        $this->d = $d;
    }
    
    function getItem(){
        return $this->d;    
    }

    function setName($name){
        $this->name = $name;    
    }
    function getName(){
        return $this->name;    
    }

    function setD(D_S2Container_ManualPropertyAssembler $d){
        $this->d = $d;
    }
    function getD(){
        return $this->d;
    }   
}
?>
