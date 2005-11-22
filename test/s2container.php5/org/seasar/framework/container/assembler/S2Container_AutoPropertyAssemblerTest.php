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
 * @package org.seasar.framework.container.assembler
 */
/**
 * @file S2Container_AutoPropertyAssemblerTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.assembler
 * @class S2Container_AutoPropertyAssemblerTest
 */

class AutoPropertyAssemblerTests extends PHPUnit2_Framework_TestCase {

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
     * testChild
     * @return 
     */
    public function testChild() {
       
        $container = new S2ContainerImpl();
        $container->register('G','g');
        $container->register('H','h');

        $g = $container->getComponent('g');
        $h = $container->getComponent('h');
        $hg = $h->getG();
        $this->assertSame($hg,$g);
    }
            
    /**
     * testValue
     * @return 
     */
    public function testValue() {
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('E','e');

        $ecd = $container->getComponentDef('e');
        $pro = new S2Container_PropertyDefImpl('name','test-test');
        $ecd->addPropertyDef($pro);

          
        $e = $container->getComponent('e');
        $this->assertEquals($e->getName(),"test-test");
    }
            
    /**
     * testNoComponent
     * @return 
     */
    public function testNoComponent() {
       
        $container = new S2ContainerImpl();
        $container->register('L','l');

        $l = $container->getComponent('l');
        $this->assertType('L', $l);
    }
}
?>