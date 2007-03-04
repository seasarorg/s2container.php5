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
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2Container_ArgDefImplTest
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

    function testGetValue() {
        $arg = new S2Container_ArgDefImpl();
        $this->assertNotNull($arg);

        $arg->setValue('test val');
        $this->assertEquals($arg->getValue(),'test val');
        
        $arg->setExpression('100');
        $this->assertEquals($arg->getValue(),100);

        $arg = new S2Container_ArgDefImpl();
        $this->assertNotNull($arg);

        $cd = new S2Container_ComponentDefImpl('A_S2Container_ArgDefImpl','a');
        $arg->setChildComponentDef($cd);
        $this->assertType('A_S2Container_ArgDefImpl',$arg->getValue());
    } 

    function testMetaDef(){
        $arg = new S2Container_ArgDefImpl();
        $this->assertNotNull($arg);
 
        $md1 = new S2Container_MetaDefImpl('a','A');
        $arg->addMetaDef($md1);
        $md2 = new S2Container_MetaDefImpl('b','B');
        $arg->addMetaDef($md2);
        $md3 = new S2Container_MetaDefImpl('c','C');
        $arg->addMetaDef($md3);

        $this->assertEquals($arg->getMetaDefSize(),3);

        $md = $arg->getMetaDef('a');
        $this->assertTrue($md === $md1);
    
        $md = $arg->getMetaDef(1);
        $this->assertTrue($md === $md2);
    }

    function testMetaDefs(){
        $arg = new S2Container_ArgDefImpl();
        $this->assertNotNull($arg);
 
        $md1 = new S2Container_MetaDefImpl('a','A1');
        $arg->addMetaDef($md1);
        $md2 = new S2Container_MetaDefImpl('a','A2');
        $arg->addMetaDef($md2);
        $md3 = new S2Container_MetaDefImpl('a','A3');
        $arg->addMetaDef($md3);

        $this->assertEquals($arg->getMetaDefSize(),3);

        $mds = $arg->getMetaDefs('a');
        $this->assertEquals(count($mds),3);
        $this->assertTrue($mds[0] === $md1);
    }
}

interface IA_S2Container_ArgDefImpl {}
class A_S2Container_ArgDefImpl implements IA_S2Container_ArgDefImpl {}
?>
