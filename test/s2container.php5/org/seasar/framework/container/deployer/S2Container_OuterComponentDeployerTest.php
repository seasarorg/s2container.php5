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
 * @package org.seasar.framework.container.deployer
 */
/**
 * @file S2Container_OuterComponentDeployerTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.deployer
 * @class S2Container_OuterComponentDeployerTest
 */
class OuterComponentDeployerTests extends PHPUnit2_Framework_TestCase {

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
     * testOuter1
     * @return 
     */
    public function testOuter1() {
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('L','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_OUTER);
          
        $l = new L();
        $this->assertNull($l->getComp());

        $container->injectDependency($l);
        $this->assertType('D', $l->getComp());
    }
            
    /**
     * testCheckComponentClass
     * @return 
     */
    public function testCheckComponentClass() {

        $cd = new S2Container_ComponentDefImpl('L','l');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_OUTER);
        
        $deployer = new S2Container_OuterComponentDeployer($cd);  

        try{
            $deployer->injectDependency(new A());
        }catch(Exception $e){
        	$this->assertType('S2Container_ClassUnmatchRuntimeException', $e);
        }
    }
}
?>