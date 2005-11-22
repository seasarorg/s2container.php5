<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
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
/**
 * @package org.seasar.framework.container.impl
 */
/**
 * @file S2Container_ComponentDefImplTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.impl
 * @class S2Container_ComponentDefImplTest
 */
class ComponentDefImplTests extends PHPUnit2_Framework_TestCase {

    /**
     * Construct Testcase
     */
    public function __construct() {
         parent::__construct();
    }

    /**
     * Setup Testcase
     */
    public function setUp() {
        parent::setUp();
    }

    /**
     * Clean up Testcase
     */
    public function tearDown() {
        parent::tearDown();
    }
            
    /**
     * testArgDefSupport
     * @return 
     */
    public function testArgDefSupport() {

        $cd = new S2Container_ComponentDefImpl('A','a');
               
        $arg1 = new S2Container_ArgDefImpl();
        $arg2 = new S2Container_ArgDefImpl();
        $arg3 = new S2Container_ArgDefImpl();
        
        $cd->addArgDef($arg1);
        $cd->addArgDef($arg2);
        $cd->addArgDef($arg3);
        
        $this->assertEquals($cd->getArgDefSize(),3);
        $arg = $cd->getArgDef(1);
        $this->assertSame($arg,$arg2);
    }
            
    /**
     * testPropertyDefSupport
     * @return 
     */
    public function testPropertyDefSupport() {

        $cd = new S2Container_ComponentDefImpl('A','a');
               
        $prop1 = new S2Container_PropertyDefImpl('a');
        $prop2 = new S2Container_PropertyDefImpl('b');
        $prop3 = new S2Container_PropertyDefImpl('c');
        
        $cd->addPropertyDef($prop1);
        $cd->addPropertyDef($prop2);
        $cd->addPropertyDef($prop3);
        
        $this->assertEquals($cd->getPropertyDefSize(),3);

        $prop = $cd->getPropertyDef(1);
        $this->assertSame($prop,$prop2);

        $prop = $cd->getPropertyDef('a');
        $this->assertSame($prop,$prop1);

        $this->assertTrue($cd->hasPropertyDef('c'));
    }
            
    /**
     * testInitMethodDefSupport
     * @return 
     */
    public function testInitMethodDefSupport() {

        $cd = new S2Container_ComponentDefImpl('A','a');
               
        $im1 = new S2Container_InitMethodDefImpl('a');
        $im2 = new S2Container_InitMethodDefImpl('b');
        $im3 = new S2Container_InitMethodDefImpl('c');
        
        $cd->addInitMethodDef($im1);
        $cd->addInitMethodDef($im2);
        $cd->addInitMethodDef($im3);
        
        $this->assertEquals($cd->getInitMethodDefSize(),3);
        $im = $cd->getInitMethodDef(1);
        $this->assertSame($im,$im2);
    }
            
    /**
     * testDestroyMethodDefSupport
     * @return 
     */
    public function testDestroyMethodDefSupport() {

        $cd = new S2Container_ComponentDefImpl('A','a');
               
        $dm1 = new S2Container_DestroyMethodDefImpl('a');
        $dm2 = new S2Container_DestroyMethodDefImpl('b');
        $dm3 = new S2Container_DestroyMethodDefImpl('c');
        
        $cd->addDestroyMethodDef($dm1);
        $cd->addDestroyMethodDef($dm2);
        $cd->addDestroyMethodDef($dm3);
        
        $this->assertEquals($cd->getDestroyMethodDefSize(),3);
        $dm = $cd->getDestroyMethodDef(1);
        $this->assertSame($dm,$dm2);
    }
            
    /**
     * testAspectDefSupport
     * @return 
     */
    public function testAspectDefSupport() {

        $cd = new S2Container_ComponentDefImpl('A','a');
               
        $aspect1 = new S2Container_AspectDefImpl('a');
        $aspect2 = new S2Container_AspectDefImpl('b');
        $aspect3 = new S2Container_AspectDefImpl('c');
        
        $cd->addAspectDef($aspect1);
        $cd->addAspectDef($aspect2);
        $cd->addAspectDef($aspect3);
        
        $this->assertEquals($cd->getAspectDefSize(),3);
        $aspect = $cd->getAspectDef(1);
        $this->assertSame($aspect,$aspect2);
    }
            
    /**
     * testMetaDefSupport
     * @return 
     */
    public function testMetaDefSupport() {

        $cd = new S2Container_ComponentDefImpl('A','a');
               
        $meta1 = new S2Container_MetaDefImpl('a');
        $meta2 = new S2Container_MetaDefImpl('b');
        $meta3 = new S2Container_MetaDefImpl('c');
        
        $cd->addMetaDef($meta1);
        $cd->addMetaDef($meta2);
        $cd->addMetaDef($meta3);
        
        $this->assertEquals($cd->getMetaDefSize(),3);
        $meta = $cd->getMetaDef(1);
        $this->assertSame($meta,$meta2);

        $meta = $cd->getMetaDef('c');
        $this->assertSame($meta,$meta3);
    }
            
    /**
     * testNotRequiredClass
     * @return 
     */
    public function testNotRequiredClass(){

        $cd = new S2Container_ComponentDefImpl('NotRequiredClass','a');
        $this->assertType('S2Container_ComponentDefImpl', $cd);
        $this->assertNull($cd->getComponentClass());
        $this->assertNull($cd->getConcreteClass());

        try{
            $cd->getComponent();
        }catch(Exception $e){
            $this->assertType('S2Container_S2RuntimeException', $e);
        }

        $this->assertFalse($cd->reconstruct());
        $s = "class NotRequiredClass{}";
        eval($s);
        $this->assertTrue($cd->reconstruct());
        $this->assertFalse($cd->reconstruct());
        $this->assertTrue($cd->reconstruct(1));
        $this->assertEquals($cd->getComponentClass()->getName(),'NotRequiredClass');
        $this->assertEquals($cd->getConcreteClass()->getName(),'NotRequiredClass');
        $a = $cd->getComponent();
        $this->assertType('NotRequiredClass', $a);
    }
}
?>