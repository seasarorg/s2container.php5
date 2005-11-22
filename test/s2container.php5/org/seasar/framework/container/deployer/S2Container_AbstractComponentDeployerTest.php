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
 * @file S2Container_AbstractComponentDeployerTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.deployer
 * @class S2Container_AbstractComponentDeployerTest
 */
class AbstractComponentDeployerTests extends PHPUnit2_Framework_TestCase {

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
     * testSetupAssemblerForAuto
     * @return 
     */
    public function testSetupAssemblerForAuto() {

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertType('S2Container_AutoConstructorAssembler', $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_AutoPropertyAssembler', $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler', $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler', $deployer->getDestroyMethodAssemblerTest());

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        $cd->setExpression("d");        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertType('S2Container_ExpressionConstructorAssembler', $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_AutoPropertyAssembler', $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler', $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler', $deployer->getDestroyMethodAssemblerTest());

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        $cd->addArgDef(new S2Container_ArgDefImpl('test'));
        $deployer = new TestComponentDeployer($cd);  

        $this->assertType('S2Container_ManualConstructorAssembler', $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_AutoPropertyAssembler', $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler', $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler', $deployer->getDestroyMethodAssemblerTest());
    }
            
    /**
     * testSetupAssemblerForConstructor
     * @return 
     */
    public function testSetupAssemblerForConstructor() {

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertType('S2Container_AutoConstructorAssembler', $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_ManualPropertyAssembler', $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler', $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler', $deployer->getDestroyMethodAssemblerTest());
    }
            
    /**
     * testSetupAssemblerForProperty
     * @return 
     */
    public function testSetupAssemblerForProperty() {

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_PROPERTY);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertType('S2Container_ManualConstructorAssembler', $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_AutoPropertyAssembler', $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler', $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler', $deployer->getDestroyMethodAssemblerTest());
    }
            
    /**
     * testSetupAssemblerForNone
     * @return 
     */
    public function testSetupAssemblerForNone() {

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_NONE);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertType('S2Container_DefaultConstructorAssembler', $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_DefaultPropertyAssembler', $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler', $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler', $deployer->getDestroyMethodAssemblerTest());

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_NONE);
        $cd->addArgDef(new S2Container_ArgDefImpl('test'));
        $cd->addPropertyDef(new S2Container_PropertyDefImpl('test','test'));
        $deployer = new TestComponentDeployer($cd);  

        $this->assertType('S2Container_ManualConstructorAssembler', $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_ManualPropertyAssembler', $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler', $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler', $deployer->getDestroyMethodAssemblerTest());
    }
}

class TestComponentDeployer extends S2Container_AbstractComponentDeployer{
    public function TestComponentDeployer(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
            
    /**
     * getConstructorAssemblerTest
     * @return 
     */
    public function getConstructorAssemblerTest(){
    	return $this->getConstructorAssembler();
    }
            
    /**
     * getPropertyAssemblerTest
     * @return 
     */
    public function getPropertyAssemblerTest(){
    	return $this->getPropertyAssembler();
    }
            
    /**
     * getInitMethodAssemblerTest
     * @return 
     */
    public function getInitMethodAssemblerTest(){
    	return $this->getInitMethodAssembler();
    }
            
    /**
     * getDestroyMethodAssemblerTest
     * @return 
     */
    public function getDestroyMethodAssemblerTest(){
    	return $this->getDestroyMethodAssembler();
    }
            
    /**
     * deploy
     * @return 
     */
    public function deploy(){}
            
    /**
     * injectDependency
     * @param $outerComponent
     * @return 
     */
    public function injectDependency($outerComponent){}
            
    /**
     * init
     * @return 
     */
    public function init(){}
            
    /**
     * destroy
     * @return 
     */
    public function destroy(){}
}
?>