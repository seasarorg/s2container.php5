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
class S2Container_AbstractAssemblerTest extends PHPUnit_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testGetBeanDesc() {
        $asm = new Test_S2Container_AbstractAssembler(
                   new S2Container_ComponentDefImpl('A_S2Container_AbstractAssembler'));       
        $this->assertType('Test_S2Container_AbstractAssembler',$asm);

        $desc = $asm->getBeanDescTest();
        $this->assertTrue($desc instanceof S2Container_BeanDesc);
    }
    
    function testGetComponentClass(){
        $asm = new Test_S2Container_AbstractAssembler(
                   new S2Container_ComponentDefImpl('A_S2Container_AbstractAssembler'));       

        $desc = $asm->getBeanDescTest(new B_S2Container_AbstractAssembler());
        $this->assertTrue($desc instanceof S2Container_BeanDesc);

        $c = $desc->getBeanClass();
        $this->assertTrue($c->getName() == 'A_S2Container_AbstractAssembler');
    }
}

class Test_S2Container_AbstractAssembler extends S2Container_AbstractAssembler{
    
    public function __consruct(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }   
    
    public function getBeanDescTest($component=null) {
        return $this->getBeanDesc($component);
    }
}

interface IA_S2Container_AbstractAssembler {}
class A_S2Container_AbstractAssembler 
    implements IA_S2Container_AbstractAssembler {}

interface IB_S2Container_AbstractAssembler {}
class B_S2Container_AbstractAssembler 
    implements IB_S2Container_AbstractAssembler{}

?>
