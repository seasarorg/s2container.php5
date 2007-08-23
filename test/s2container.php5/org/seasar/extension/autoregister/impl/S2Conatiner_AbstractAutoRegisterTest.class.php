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
 * @package org.seasar.framework.extension.autoregister.impl
 * @author klove
 */
class S2Conatiner_AbstractAutoRegisterTest
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

    function testInstantiate() {
        $register = new Test_S2Container_AbstractAutoRegister();
        $this->assertType('S2Container_AbstractAutoRegister',$register);
    }

    function testHasComponentDef() {
        $register = new Test_S2Container_AbstractAutoRegister();
        $register->setContainer(new S2ContainerImpl());
        $this->assertFalse($register->hasComponentDef('Test'));
    }

    function testFindComponentDef() {
        $register = new Test_S2Container_AbstractAutoRegister();
        $container = new S2ContainerImpl();
        $container->register(new S2Container_ComponentDefImpl(
                             'A_S2Container_AbstractAutoRegister',
                             'a'));
        $register->setContainer($container);
        $cd = $register->findComponentDef('a');
        $this->assertType('S2Container_ComponentDefImpl',$cd);
        $a = $cd->getComponent();
        $this->assertType('A_S2Container_AbstractAutoRegister',$a);

        $cd = $register->findComponentDef('b');
        $this->assertEquals($cd,null);
    }

    function testIsIgnore() {
        $register = new Test_S2Container_AbstractAutoRegister();
        $cp = new S2Container_ClassPattern();
        $cp->setShortClassNames('Dao,Service$');
        $register->addIgnoreClassPatternInternal($cp);

        $this->assertTrue($register->isIgnore('HogeService'));
        $this->assertFalse($register->isIgnore('ServiceA'));
    }
}

class A_S2Container_AbstractAutoRegister{}
    
class Test_S2Container_AbstractAutoRegister
    extends S2Container_AbstractAutoRegister{

    public function registerAll(){}
    
    public function isIgnore($name){
        return parent::isIgnore($name);    
    }

    public function hasComponentDef($name){
        return parent::hasComponentDef($name);    
    }

    public function findComponentDef($name){
        return parent::findComponentDef($name);    
    }
    
    public function addIgnoreClassPatternInternal(S2Container_ClassPattern $pattern){
        parent::addIgnoreClassPatternInternal($pattern);    
    }
}
?>
