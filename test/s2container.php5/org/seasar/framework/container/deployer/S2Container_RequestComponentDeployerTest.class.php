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
class S2Container_RequestComponentDeployerTest
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

    function testRequest1() {
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_RequestComponentDeployer','d');
        $container->register('L_S2Container_RequestComponentDeployer','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_REQUEST);
          
        $l = $container->getComponent('l');
        $this->assertType('D_S2Container_RequestComponentDeployer',$l->getComp());

        $ll = $container->getComponent('l');
        $this->assertTrue($l === $ll);
    }
   
    function testRequest2() {
        $_REQUEST['l'] = "test string";
          
        $container = new S2ContainerImpl();
        $container->register('D_S2Container_RequestComponentDeployer','d');
        $container->register('L_S2Container_RequestComponentDeployer','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_REQUEST);
          
        $l = $container->getComponent('l');
        $this->assertType('L_S2Container_RequestComponentDeployer',$l);
    }
}

interface IG_S2Container_RequestComponentDeployer{}
class D_S2Container_RequestComponentDeployer 
    implements IG_S2Container_RequestComponentDeployer{}
    
class L_S2Container_RequestComponentDeployer {

    private $comp;
    function setComp(IG_S2Container_RequestComponentDeployer $comp){
        $this->comp = $comp;
    }

    function getComp(){
        return $this->comp;
    }
}
?>
