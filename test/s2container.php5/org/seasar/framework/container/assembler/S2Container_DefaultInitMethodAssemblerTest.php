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
 * @file S2Container_DefaultInitMethodAssemblerTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.assembler
 * @class S2Container_DefaultInitMethodAssemblerTest
 */
class DefaultInitMethodAssemblerTests extends PHPUnit2_Framework_TestCase {

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
     * testNoArgs
     * @return 
     */
    public function testNoArgs() {
       
        $container = new S2ContainerImpl();
        $container->register('I','i');

        $cd = $container->getComponentDef('i');
        $me = new S2Container_InitMethodDefImpl('culc');
        $cd->addInitMethodDef($me);

          
        $i = $container->getComponent('i');
        $this->assertEquals($i->getResult(),2);
    }
            
    /**
     * testNoArgs
     * @return 
     */
    public function testNoArgsAuto() {
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('I','i');

        $cd = $container->getComponentDef('i');
        $me = new S2Container_InitMethodDefImpl('culc3');
        $cd->addInitMethodDef($me);
          
        $i = $container->getComponent('i');
        $this->assertEquals($i->getResult(),4);
    }
            
    /**
     * testWithArgs
     * @return 
     */
    public function testWithArgs() {
       
        $container = new S2ContainerImpl();
        $container->register('I','i');

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
?>