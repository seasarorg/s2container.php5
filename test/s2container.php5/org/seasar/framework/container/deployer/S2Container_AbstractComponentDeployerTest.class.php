<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
class S2Container_AbstractComponentDeployerTest
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

    function testSetupAssemblerForAuto() {
        $cd = new S2Container_ComponentDefImpl('C_S2Container_AbstractComponentDeployer','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        
        $deployer = new Test_S2Container_AbstractComponentDeployer($cd);  

        $this->assertType('S2Container_AutoConstructorAssembler',
                         $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_AutoPropertyAssembler',
                         $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler',
                         $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler',
                         $deployer->getDestroyMethodAssemblerTest());

        $cd = new S2Container_ComponentDefImpl('C_S2Container_AbstractComponentDeployer','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        $cd->setExpression("d");        
        $deployer = new Test_S2Container_AbstractComponentDeployer($cd);  

        $this->assertType('S2Container_ExpressionConstructorAssembler',
                         $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_AutoPropertyAssembler',
                         $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler',
                         $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler',
                         $deployer->getDestroyMethodAssemblerTest());

        $cd = new S2Container_ComponentDefImpl('C_S2Container_AbstractComponentDeployer','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        $cd->addArgDef(new S2Container_ArgDefImpl('test'));
        $deployer = new Test_S2Container_AbstractComponentDeployer($cd);  

        $this->assertType('S2Container_ManualConstructorAssembler',
                         $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_AutoPropertyAssembler',
                         $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler',
                         $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler',
                         $deployer->getDestroyMethodAssemblerTest());
    }

    function testSetupAssemblerForConstructor() {
        $cd = new S2Container_ComponentDefImpl('C_S2Container_AbstractComponentDeployer','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        
        $deployer = new Test_S2Container_AbstractComponentDeployer($cd);  

        $this->assertType('S2Container_AutoConstructorAssembler',
                         $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_ManualPropertyAssembler',
                         $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler',
                         $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler',
                         $deployer->getDestroyMethodAssemblerTest());
    }

    function testSetupAssemblerForProperty() {
        $cd = new S2Container_ComponentDefImpl('C_S2Container_AbstractComponentDeployer','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_PROPERTY);
        
        $deployer = new Test_S2Container_AbstractComponentDeployer($cd);  

        $this->assertType('S2Container_ManualConstructorAssembler',
                         $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_AutoPropertyAssembler',
                         $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler',
                         $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler',
                         $deployer->getDestroyMethodAssemblerTest());
    }

    function testSetupAssemblerForNone() {
        $cd = new S2Container_ComponentDefImpl('C_S2Container_AbstractComponentDeployer','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_NONE);
        
        $deployer = new Test_S2Container_AbstractComponentDeployer($cd);  

        $this->assertType('S2Container_DefaultConstructorAssembler',
                         $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_DefaultPropertyAssembler',
                         $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler',
                         $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler',
                         $deployer->getDestroyMethodAssemblerTest());

        $cd = new S2Container_ComponentDefImpl('C_S2Container_AbstractComponentDeployer','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_NONE);
        $cd->addArgDef(new S2Container_ArgDefImpl('test'));
        $cd->addPropertyDef(new S2Container_PropertyDefImpl('test','test'));
        $deployer = new Test_S2Container_AbstractComponentDeployer($cd);  

        $this->assertType('S2Container_ManualConstructorAssembler',
                         $deployer->getConstructorAssemblerTest());
        $this->assertType('S2Container_ManualPropertyAssembler',
                         $deployer->getPropertyAssemblerTest());
        $this->assertType('S2Container_DefaultInitMethodAssembler',
                         $deployer->getInitMethodAssemblerTest());
        $this->assertType('S2Container_DefaultDestroyMethodAssembler',
                         $deployer->getDestroyMethodAssemblerTest());
    }
}

class Test_S2Container_AbstractComponentDeployer
    extends S2Container_AbstractComponentDeployer{
    public function __construct(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }   

    public function getConstructorAssemblerTest(){
        return $this->getConstructorAssembler();
    }

    public function getPropertyAssemblerTest(){
        return $this->getPropertyAssembler();
    }

    public function getInitMethodAssemblerTest(){
        return $this->getInitMethodAssembler();
    }

    public function getDestroyMethodAssemblerTest(){
        return $this->getDestroyMethodAssembler();
    }
    
    public function deploy(){}
    
    public function injectDependency($outerComponent){}
    
    public function init(){}
    
    public function destroy(){}
}

class C_S2Container_AbstractComponentDeployer {
    private $name;
    function __construct($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}
?>
