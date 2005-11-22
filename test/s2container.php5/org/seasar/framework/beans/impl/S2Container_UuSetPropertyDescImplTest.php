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
 * @package org.seasar.framework.beans.impl
 */
/**
 * @file S2Container_UuSetPropertyDescImplTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.beans.impl
 * @class S2Container_UuSetPropertyDescImplTest
 */
class UuSetPropertyDescImplTests extends PHPUnit2_Framework_TestCase {

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
     * testAutoValueUUSet
     * @return 
     */
    public function testAutoValueUUSet() {
    
       $container = new S2ContainerImpl();
       $container->register('M','m');

       $ecd = $container->getComponentDef('m');
       $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
       $pro = new S2Container_PropertyDefImpl('name','test-test');
       $ecd->addPropertyDef($pro);
          
       $m = $container->getComponent('m');
       $this->assertEquals($m->getName(),"test-test");
   }
            
    /**
     * testAutoValueUUSet
     * @return 
     */
    public function testAutoValueUUSet2() {
    
       $container = new S2ContainerImpl();
       $container->register('M2','m');

       $ecd = $container->getComponentDef('m');
       $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
       $pro = new S2Container_PropertyDefImpl('val','test-test');
       $ecd->addPropertyDef($pro);
          
       $m = $container->getComponent('m');
       $this->assertEquals($m->getValue(),"test-test");
   }
            
    /**
     * testAutoValueUUSet
     * @return 
     */
    public function testAutoValueUUSet3() {
    
       $container = new S2ContainerImpl();
       $container->register('M3','m');

       $ecd = $container->getComponentDef('m');
       $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
       $pro = new S2Container_PropertyDefImpl('val','test-test');
       $ecd->addPropertyDef($pro);

       try{          
           $m = $container->getComponent('m');
       }catch(Exception $e){
       	   $this->assertType('S2Container_PropertyNotFoundRuntimeException', $e);
       }
   }
}

class M3 {	
}
?>
