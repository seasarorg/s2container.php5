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
 * @file S2Container_ExpressionConstructorAssemblerTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.assembler
 * @class S2Container_ExpressionConstructorAssemblerTest
 */
class ExpressionConstructorAssemblerTests extends PHPUnit2_Framework_TestCase {

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
     * testExp
     * @return 
     */
    public function testExp() {
       
        $cd = new S2Container_ComponentDefImpl('','dd');
        $cd->setExpression("return new D();");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
          
        $d = $container->getComponent('dd');
        $this->assertType('D', $d);
    }
            
    /**
     * testGetComponent
     * @return 
     */
    public function testGetComponent() {
       
        $cd = new S2Container_ComponentDefImpl('','dd');
        $cd->setExpression("d");
        
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register($cd);
          
        $d = $container->getComponent('d');
        $this->assertType('D', $d);

        $dd = $container->getComponent('dd');
        $this->assertType('D', $dd);

        $this->assertSame($d,$dd);
    }
            
    /**
     * testNotObjectException
     * @return 
     */
    public function testNotObjectException() {
       
        $cd = new S2Container_ComponentDefImpl('','dd');
        $cd->setExpression("return 100;");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
     
        try{
            $dd = $container->getComponent('dd');
        }catch(Exception $e ){
            $this->assertType('S2Container_S2RuntimeException', $e);
        }
    }
            
    /**
     * testClassUnmatchException
     * @return 
     */
    public function testClassUnmatchException() {
       
        $cd = new S2Container_ComponentDefImpl('C','dd');
        $cd->setExpression("return new D();");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
     
        try{
            $dd = $container->getComponent('dd');
        }catch(Exception $e ){
            $this->assertType('S2Container_ClassUnmatchRuntimeException', $e);
        }
    }           
}
?>