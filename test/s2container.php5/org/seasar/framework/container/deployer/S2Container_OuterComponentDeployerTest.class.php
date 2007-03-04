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
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
class S2Container_OuterComponentDeployerTest
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

    function testOuter1() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_OuterComponentDeployer','d');
        $container->register('L_S2Container_OuterComponentDeployer','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_OUTER);
          
        $l = new L_S2Container_OuterComponentDeployer();
        $this->assertNull($l->getComp());

        $container->injectDependency($l);
        $this->assertType('D_S2Container_OuterComponentDeployer',$l->getComp());
    }

    function testCheckComponentClass() {
        $cd = new S2Container_ComponentDefImpl('L_S2Container_OuterComponentDeployer','l');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_OUTER);
        
        $deployer = new S2Container_OuterComponentDeployer($cd);  

        try{
            $deployer->injectDependency(new A_S2Container_OuterComponentDeployer());
        }catch(Exception $e){
            $this->assertType('S2Container_ClassUnmatchRuntimeException',$e);
            print $e->getMessage() . "\n";
        }    
    }
}

class A_S2Container_OuterComponentDeployer{}

interface IG_S2Container_OuterComponentDeployer{}
class D_S2Container_OuterComponentDeployer 
    implements IG_S2Container_OuterComponentDeployer{}

class L_S2Container_OuterComponentDeployer {
    private $comp;

    function setComp(IG_S2Container_OuterComponentDeployer $comp){
        $this->comp = $comp;
    }

    function getComp(){
        return $this->comp;
    }
}
?>
