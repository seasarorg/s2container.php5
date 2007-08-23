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
class S2Container_ManualPropertyAssemblerTest
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

    function testUuSet() {
        $container = new S2ContainerImpl();
        $container->register('a_S2Container_ManualPropertyAssembler','a');

        $cd = $container->getComponentDef('a');
        $pro = new S2Container_PropertyDefImpl('name', 'seasar');
        $cd->addPropertyDef($pro);

        $pro = new S2Container_PropertyDefImpl('year', '2007');
        $cd->addPropertyDef($pro);

        $a = $container->getComponent('a');
        $this->assertEquals($a->name, 'seasar');

        $this->assertEquals($a->getYear(), '2007');
    }

    function testArrayChild() {
        $container = new S2ContainerImpl();
        $container->register('A_S2Container_ManualPropertyAssembler','a');
        $container->register('B_S2Container_ManualPropertyAssembler','b');
        $container->register('C_S2Container_ManualPropertyAssembler','c');

        $cd = $container->getComponentDef('a');
        $pro = new S2Container_PropertyDefImpl('ib',null);
        $pro->setChildComponentDef($container->getComponentDef('IB_S2Container_ManualPropertyAssembler'));
        $cd->addPropertyDef($pro);

        $a = $container->getComponent('a');
        $ibs = $a->getIb();
        $this->assertEquals(count($ibs), 2);
        $this->assertTrue($ibs[0] instanceof B_S2Container_ManualPropertyAssembler);
        $this->assertTrue($ibs[1] instanceof C_S2Container_ManualPropertyAssembler);
    }

    function testArrayObjectChild() {
        $container = new S2ContainerImpl();
        $container->register('F_S2Container_ManualPropertyAssembler','f');
        $container->register('B_S2Container_ManualPropertyAssembler','b');
        $container->register('C_S2Container_ManualPropertyAssembler','c');

        $cd = $container->getComponentDef('f');
        $pro = new S2Container_PropertyDefImpl('ib',null);
        $pro->setChildComponentDef($container->getComponentDef('IB_S2Container_ManualPropertyAssembler'));
        $cd->addPropertyDef($pro);

        $a = $container->getComponent('f');
        $ibs = $a->getIb();
        $this->assertEquals(count($ibs), 2);
        $this->assertTrue($ibs instanceof ArrayObject);
        $this->assertTrue($ibs[0] instanceof B_S2Container_ManualPropertyAssembler);
        $this->assertTrue($ibs[1] instanceof C_S2Container_ManualPropertyAssembler);
    }
}

class A_S2Container_ManualPropertyAssembler {
    public function __set($name, $value) {
        $this->$name = $value;
    }

    private $year;
    public function getYear() {
        return $this->year;
    }

    private $ib;
    public function setIb(array $ib) {
        $this->ib = $ib;
    }
    public function getIb() {
        return $this->ib;
    }
}

interface IB_S2Container_ManualPropertyAssembler {}
class B_S2Container_ManualPropertyAssembler 
    implements IB_S2Container_ManualPropertyAssembler{}

class C_S2Container_ManualPropertyAssembler 
    implements IB_S2Container_ManualPropertyAssembler{}

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

class F_S2Container_ManualPropertyAssembler {
    public function __set($name, $value) {
        $this->$name = $value;
    }

    private $year;
    public function getYear() {
        return $this->year;
    }

    private $ib;
    public function setIb(ArrayObject $ib) {
        $this->ib = $ib;
    }
    public function getIb() {
        return $this->ib;
    }
}
?>
