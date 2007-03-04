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
class S2Container_AutoConstructorAssemblerTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testAutoValue() {
        $container = new S2ContainerImpl();
        $container->register('C_S2Container_AutoConstructorAssembler','c');
        $c = $container->getComponent('c');
        $this->assertNull($c->say());
    }

    function testAutoChild() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_AutoConstructorAssembler','d');
        $container->register('F_S2Container_AutoConstructorAssembler','f');
          
        $d = $container->getComponent('d');
        $f = $container->getComponent('f');
        $this->assertEquals($f->getItem(),$d);
    }
}

class C_S2Container_AutoConstructorAssembler {
    private $name;
    function construct($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}

interface IG_S2Container_AutoConstructorAssembler{}
class D_S2Container_AutoConstructorAssembler 
    implements IG_S2Container_AutoConstructorAssembler{}
    
class F_S2Container_AutoConstructorAssembler {
    private $d;
    
    function __construct(IG_S2Container_AutoConstructorAssembler $d) {
        $this->d = $d;
    }
    
    function getItem(){
        return $this->d;
    }
}
?>
