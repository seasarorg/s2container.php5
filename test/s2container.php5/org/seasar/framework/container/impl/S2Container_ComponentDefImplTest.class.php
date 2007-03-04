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
class S2Container_ComponentDefImplTest
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

    function testArgDefSupport() {
        $cd = new S2Container_ComponentDefImpl('A_S2Container_ComponentDefImpl','a');
               
        $arg1 = new S2Container_ArgDefImpl();
        $arg2 = new S2Container_ArgDefImpl();
        $arg3 = new S2Container_ArgDefImpl();
        
        $cd->addArgDef($arg1);
        $cd->addArgDef($arg2);
        $cd->addArgDef($arg3);
        
        $this->assertEquals($cd->getArgDefSize(),3);
        $arg = $cd->getArgDef(1);
        $this->assertTrue($arg === $arg2);
    } 

    function testPropertyDefSupport() {
        $cd = new S2Container_ComponentDefImpl('A_S2Container_ComponentDefImpl','a');
               
        $prop1 = new S2Container_PropertyDefImpl('a');
        $prop2 = new S2Container_PropertyDefImpl('b');
        $prop3 = new S2Container_PropertyDefImpl('c');
        
        $cd->addPropertyDef($prop1);
        $cd->addPropertyDef($prop2);
        $cd->addPropertyDef($prop3);
        
        $this->assertEquals($cd->getPropertyDefSize(),3);

        $prop = $cd->getPropertyDef(1);
        $this->assertTrue($prop === $prop2);

        $prop = $cd->getPropertyDef('a');
        $this->assertTrue($prop === $prop1);

        $this->assertTrue($cd->hasPropertyDef('c'));
    } 

    function testInitMethodDefSupport() {
        $cd = new S2Container_ComponentDefImpl('A_S2Container_ComponentDefImpl','a');
               
        $im1 = new S2Container_InitMethodDefImpl('a');
        $im2 = new S2Container_InitMethodDefImpl('b');
        $im3 = new S2Container_InitMethodDefImpl('c');
        
        $cd->addInitMethodDef($im1);
        $cd->addInitMethodDef($im2);
        $cd->addInitMethodDef($im3);
        
        $this->assertEquals($cd->getInitMethodDefSize(),3);
        $im = $cd->getInitMethodDef(1);
        $this->assertTrue($im === $im2);
    } 

    function testDestroyMethodDefSupport() {
        $cd = new S2Container_ComponentDefImpl('A_S2Container_ComponentDefImpl','a');
               
        $dm1 = new S2Container_DestroyMethodDefImpl('a');
        $dm2 = new S2Container_DestroyMethodDefImpl('b');
        $dm3 = new S2Container_DestroyMethodDefImpl('c');
        
        $cd->addDestroyMethodDef($dm1);
        $cd->addDestroyMethodDef($dm2);
        $cd->addDestroyMethodDef($dm3);
        
        $this->assertEquals($cd->getDestroyMethodDefSize(),3);
        $dm = $cd->getDestroyMethodDef(1);
        $this->assertTrue($dm === $dm2);
    } 

    function testAspectDefSupport() {
        $cd = new S2Container_ComponentDefImpl('A_S2Container_ComponentDefImpl','a');
               
        $aspect1 = new S2Container_AspectDefImpl('a');
        $aspect2 = new S2Container_AspectDefImpl('b');
        $aspect3 = new S2Container_AspectDefImpl('c');
        
        $cd->addAspectDef($aspect1);
        $cd->addAspectDef($aspect2);
        $cd->addAspectDef($aspect3);
        
        $this->assertEquals($cd->getAspectDefSize(),3);
        $aspect = $cd->getAspectDef(1);
        $this->assertTrue($aspect === $aspect2);
    } 

    function testMetaDefSupport() {
        $cd = new S2Container_ComponentDefImpl('A_S2Container_ComponentDefImpl','a');
               
        $meta1 = new S2Container_MetaDefImpl('a');
        $meta2 = new S2Container_MetaDefImpl('b');
        $meta3 = new S2Container_MetaDefImpl('c');
        
        $cd->addMetaDef($meta1);
        $cd->addMetaDef($meta2);
        $cd->addMetaDef($meta3);
        
        $this->assertEquals($cd->getMetaDefSize(),3);
        $meta = $cd->getMetaDef(1);
        $this->assertTrue($meta === $meta2);

        $meta = $cd->getMetaDef('c');
        $this->assertTrue($meta === $meta3);
    } 

    function testNotRequiredClass(){
        $cd = new S2Container_ComponentDefImpl('NotRequiredClass_S2Container_ComponentDefImpl','a');
        $this->assertType('S2Container_ComponentDefImpl',$cd);
        $this->assertNull($cd->getComponentClass());
        $this->assertNull($cd->getConcreteClass());
        try{
            $cd->getComponent();
        }catch(Exception $e){
            $this->assertType('S2Container_S2RuntimeException',$e);
            print $e->getMessage()."\n";
        }

        $this->assertFalse($cd->reconstruct());
        $s = "class NotRequiredClass_S2Container_ComponentDefImpl{}";
        eval($s);
        $this->assertTrue($cd->reconstruct());
        $this->assertFalse($cd->reconstruct());
        $this->assertTrue($cd->reconstruct(1));
        $this->assertEquals($cd->getComponentClass()->getName(),'NotRequiredClass_S2Container_ComponentDefImpl');
        $this->assertEquals($cd->getConcreteClass()->getName(),'NotRequiredClass_S2Container_ComponentDefImpl');
        $a = $cd->getComponent();
        $this->assertType('NotRequiredClass_S2Container_ComponentDefImpl',$a);
    }

    function testArrayObject() {
        $cd = new S2Container_ComponentDefImpl('ArrayObject','a');
        $ad = new S2Container_ArgDefImpl(array());
        $cd->addArgDef($ad);        
        
        $this->assertType('ArrayObject',$cd->getComponent());
    } 
}

interface IA_S2Container_ComponentDefImpl {}
class A_S2Container_ComponentDefImpl implements IA_S2Container_ComponentDefImpl {}

?>
