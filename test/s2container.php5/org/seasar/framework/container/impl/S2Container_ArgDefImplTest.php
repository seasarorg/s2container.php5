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
 * @file S2Container_ArgDefImplTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.impl
 * @class S2Container_ArgDefImplTest
 */
class ArgDefImplTests extends PHPUnit2_Framework_TestCase {

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
     * testGetValue
     * @return 
     */
    public function testGetValue() {
       
        $arg = new S2Container_ArgDefImpl();
        $this->assertNotNull($arg);

        $arg->setValue('test val');
        $this->assertEquals($arg->getValue(),'test val');
        
        $arg->setExpression('100');
        $this->assertEquals($arg->getValue(),'100');

        $arg = new S2Container_ArgDefImpl();
        $this->assertNotNull($arg);

        $cd = new S2Container_ComponentDefImpl('A','a');
        $arg->setChildComponentDef($cd);
        $this->assertType('A', $arg->getValue());
    }
            
    /**
     * testMetaDef
     * @return 
     */
    public function testMetaDef(){

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
        $this->assertSame($md,$md1);
    
        $md = $arg->getMetaDef(1);
        $this->assertSame($md,$md2);
    }
            
    /**
     * testMetaDef
     * @return 
     */
    public function testMetaDefs(){

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
        $this->assertSame($mds[0],$md1);
    }
}
?>