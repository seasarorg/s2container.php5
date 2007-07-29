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
class S2Container_AutoPropertyAssemblerTest 
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

    function testChild() {
        $container = new S2ContainerImpl();
        $container->register('G_S2Container_AutoPropertyAssembler','g');
        $container->register('H_S2Container_AutoPropertyAssembler','h');

        $g = $container->getComponent('g');
        $h = $container->getComponent('h');
        $hg = $h->getG();
        $this->assertTrue($hg === $g);
    }

    function testValue() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_AutoPropertyAssembler','d');
        $container->register('E_S2Container_AutoPropertyAssembler','e');

        $ecd = $container->getComponentDef('e');
        $pro = new S2Container_PropertyDefImpl('name','test-test');
        $ecd->addPropertyDef($pro);

          
        $e = $container->getComponent('e');
        $this->assertEquals($e->getName(),"test-test");
    }

    function testNoComponent() {
        $container = new S2ContainerImpl();
        $container->register('L_S2Container_AutoPropertyAssembler','l');

        $l = $container->getComponent('l');
        $this->assertType('L_S2Container_AutoPropertyAssembler',$l);
    }

    function testClassInjection() {
        $container = new S2ContainerImpl();
        $container->register('A_S2Container_AutoPropertyAssembler','a');
        $container->register('B_S2Container_AutoPropertyAssembler','b');

        $a = $container->getComponent('a');
        if (defined('S2CONTAINER_PHP5_AUTO_DI_INTERFACE') and
            S2CONTAINER_PHP5_AUTO_DI_INTERFACE === true){
            $this->assertEquals(1,$a->getB());
        } else {
            $this->assertType('B_S2Container_AutoPropertyAssembler',$a->getB());
        }
    }
}

class A_S2Container_AutoPropertyAssembler {
    private $b = 1;
    public function setB(B_S2Container_AutoPropertyAssembler $b){
        $this->b = $b;
    }
    public function getB() {
        return $this->b;
    }
}

class B_S2Container_AutoPropertyAssembler {}

class D_S2Container_AutoPropertyAssembler
    implements IG_S2Container_AutoPropertyAssembler{}

class E_S2Container_AutoPropertyAssembler {
    private $d;
    private $name;
    function __construct(IG_S2Container_AutoPropertyAssembler $d) {
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

    function setD(D_S2Container_AutoPropertyAssembler $d){
        $this->d = $d;
    }
    function getD(){
        return $this->d;
    }
}

interface IG_S2Container_AutoPropertyAssembler {}
class G_S2Container_AutoPropertyAssembler 
    implements IG_S2Container_AutoPropertyAssembler {
    function finish(){
        print "destroy class G \n";
    }

    function finish2($msg){
        print "$msg G \n";
    }
}

class H_S2Container_AutoPropertyAssembler {
    private $g;
    private $name;
    function __construct(IG_S2Container_AutoPropertyAssembler $g) {
        $this->g = $g;
    }
    
    function getItem(){
        return $this->g;    
    }

    function setName($name){
        $this->name = $name;    
    }
    function getName(){
        return $this->name;    
    }

    function setG(IG_S2Container_AutoPropertyAssembler $g){
        $this->g = $g;
    }
    function getG(){
        return $this->g;
    }
}

class L_S2Container_AutoPropertyAssembler {
    private $comp;

    function setComp(IG_S2Container_AutoPropertyAssembler $comp){
        $this->comp = $comp;
    }

    function getComp(){
        return $this->comp;
    }
}
?>
