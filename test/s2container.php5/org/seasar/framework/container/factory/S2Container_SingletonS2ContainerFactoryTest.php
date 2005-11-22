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
 * @package org.seasar.framework.container.factory
 */
/**
 * @file S2Container_SingletonS2ContainerFactoryTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.factory
 * @class S2Container_SingletonS2ContainerFactoryTest
 */
class SingletonS2ContainerFactoryTests extends PHPUnit2_Framework_TestCase {

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

/*
    function testGetConfigPath() {
       
        $this->assertEquals(S2Container_SingletonS2ContainerFactory::getConfigPath(),
                           "/path/to/app.dicon");
    }
*/

    function testInit() {
       
        S2Container_SingletonS2ContainerFactory::init();
        $container = S2Container_SingletonS2ContainerFactory::getContainer();
        $a = $container->getComponent('a');
        $this->assertType('A', $a);
    }
            
    /**
     * testInitWithPath
     * @return 
     */
    public function testInitWithPath() {

        $path = TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test1.dicon';    
        S2Container_SingletonS2ContainerFactory::init($path);
        $container = S2Container_SingletonS2ContainerFactory::getContainer();
        $this->assertNotNull($container);
       
        $a = $container->getComponent('a');
        $this->assertType('A', $a);

        $b = $container->getComponent('test1.b');
        $this->assertType('A', $b);
    }
            
    /**
     * testInitWithPath
     * @return 
     */
    public function testInitWithPathNotExist() {

        $path = TEST_DIR . '/test1.dicon';  
        try{  
            S2Container_SingletonS2ContainerFactory::init($path);
        }catch(Exception $e){
            $this->assertType('S2Container_S2RuntimeException', $e);
        }
    }
}
?>