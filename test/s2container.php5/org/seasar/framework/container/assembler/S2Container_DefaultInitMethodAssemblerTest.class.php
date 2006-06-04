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
class S2Container_DefaultInitMethodAssemblerTest
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
}

interface IG_S2Container_DefaultInitMethodAssembler{}
class D_S2Container_DefaultInitMethodAssembler
    implements IG_S2Container_DefaultInitMethodAssembler{}

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
?>
