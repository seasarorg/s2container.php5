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
class S2Container_DefaultInitMethodAssemblerTest
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

    function testNoArgs() {
        $container = new S2ContainerImpl();
        $container->register('I_S2Container_DefaultInitMethodAssembler','i');

        $cd = $container->getComponentDef('i');
        $me = new S2Container_InitMethodDefImpl('culc');
        $cd->addInitMethodDef($me);
          
        $i = $container->getComponent('i');
        $this->assertEquals($i->getResult(),2);
    }

    function testNoArgsAuto() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_DefaultInitMethodAssembler','d');
        $container->register('I_S2Container_DefaultInitMethodAssembler','i');

        $cd = $container->getComponentDef('i');
        $me = new S2Container_InitMethodDefImpl('culc3');
        $cd->addInitMethodDef($me);
          
        $i = $container->getComponent('i');
        $this->assertEquals($i->getResult(),4);
    }
   
    function testWithArgs() {
        $container = new S2ContainerImpl();
        $container->register('I_S2Container_DefaultInitMethodAssembler','i');

        $cd = $container->getComponentDef('i');
        $me = new S2Container_InitMethodDefImpl('culc2');
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("2");
        $me->addArgDef($arg);
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("3");
        $me->addArgDef($arg);
          
        $cd->addInitMethodDef($me);
        $i = $container->getComponent('i');
        $this->assertEquals($i->getResult(),5);
    }

    function testArrayChildComponentDef() {
        $container = new S2ContainerImpl();
        $container->register('A_S2Container_DefaultInitMethodAssembler','a');
        $container->register('BImpl_S2Container_DefaultInitMethodAssembler','b');
        $container->register('CImpl_S2Container_DefaultInitMethodAssembler','c');
          
        $cd = $container->getComponentDef('a');
        $im = new S2Container_InitMethodDefImpl('setupIb');
        $cd->addInitMethodDef($im);

        $arg = new S2Container_ArgDefImpl();
        $arg->setChildComponentDef($container->getComponentDef('B_S2Container_DefaultInitMethodAssembler'));
        $im->addArgDef($arg);

        $a = $container->getComponent('a');
        $ib = $a->getIb();
        $this->assertEquals(count($ib), 2);
        $this->assertTrue($ib[0] instanceof BImpl_S2Container_DefaultInitMethodAssembler);
        $this->assertTrue($ib[1] instanceof CImpl_S2Container_DefaultInitMethodAssembler);
    }

    function testArrayObjectChildComponentDef() {
        $container = new S2ContainerImpl();
        $container->register('J_S2Container_DefaultInitMethodAssembler','j');
        $container->register('BImpl_S2Container_DefaultInitMethodAssembler','b');
        $container->register('CImpl_S2Container_DefaultInitMethodAssembler','c');
          
        $cd = $container->getComponentDef('j');
        $im = new S2Container_InitMethodDefImpl('setupIb');
        $cd->addInitMethodDef($im);

        $arg = new S2Container_ArgDefImpl();
        $arg->setChildComponentDef($container->getComponentDef('B_S2Container_DefaultInitMethodAssembler'));
        $im->addArgDef($arg);

        $a = $container->getComponent('j');
        $ib = $a->getIb();
        $this->assertEquals(count($ib), 2);
        $this->assertTrue($ib instanceof ArrayObject);
        $this->assertTrue($ib[0] instanceof BImpl_S2Container_DefaultInitMethodAssembler);
        $this->assertTrue($ib[1] instanceof CImpl_S2Container_DefaultInitMethodAssembler);
    }

    function testClassInjection() {
        $container = new S2ContainerImpl();
        $container->register('E_S2Container_DefaultInitMethodAssembler','e');
        $container->register('F_S2Container_DefaultInitMethodAssembler','f');

        $cd = $container->getComponentDef('e');
        $im = new S2Container_InitMethodDefImpl('setupF');
        $cd->addInitMethodDef($im);

        $e = $container->getComponent('e');
        if (defined('S2CONTAINER_PHP5_AUTO_DI_INTERFACE') and
            S2CONTAINER_PHP5_AUTO_DI_INTERFACE === true){
            $this->assertEquals(null,$e->getF());
        } else {
            $this->assertType('F_S2Container_DefaultInitMethodAssembler',$e->getF());
        }
    }
}

class A_S2Container_DefaultInitMethodAssembler {
    private $ib;

    public function setupIb(array $ib) {
        $this->ib = $ib;
    }

    public function getIb() {
        return $this->ib;
    }
}

interface B_S2Container_DefaultInitMethodAssembler {}
class BImpl_S2Container_DefaultInitMethodAssembler
    implements B_S2Container_DefaultInitMethodAssembler {}
class CImpl_S2Container_DefaultInitMethodAssembler
    implements B_S2Container_DefaultInitMethodAssembler {}

interface IG_S2Container_DefaultInitMethodAssembler{}
class D_S2Container_DefaultInitMethodAssembler
    implements IG_S2Container_DefaultInitMethodAssembler{}

class E_S2Container_DefaultInitMethodAssembler {
    private $f;

    public function setupF(F_S2Container_DefaultInitMethodAssembler $f = null){
        $this->f = $f;
    }
    public function getF() {
        return $this->f;
    }
}

class F_S2Container_DefaultInitMethodAssembler {}

class I_S2Container_DefaultInitMethodAssembler {
    private $result = -1;

    function culc(){
        $this->result = 1+1;
    }

    function culc2($a,$b){
        $this->result = $a+$b;
    }

    function culc3(IG_S2Container_DefaultInitMethodAssembler $d){
        if($d instanceof D_S2Container_DefaultInitMethodAssembler){
            $this->result = 4;
        }else{
            return -1;
        }
    }
    
    function getResult(){
        return $this->result;
    }
}

class J_S2Container_DefaultInitMethodAssembler {
    private $ib;

    public function setupIb(ArrayObject $ib) {
        $this->ib = $ib;
    }

    public function getIb() {
        return $this->ib;
    }
}
?>
