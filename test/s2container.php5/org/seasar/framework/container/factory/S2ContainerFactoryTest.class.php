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
 * @package org.seasar.framework.container.factory
 * @author klove
 */
class S2ContainerFactoryTest
    extends PHPUnit2_Framework_TestCase {

    private $diconDir;
    public function __construct($name) {
        parent::__construct($name);
        $this->diconDir = dirname(__FILE__) . '/dicon/' . __CLASS__;
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testGetBuilder() {
        $diconFile = $this->diconDir . '/testGetBuilder.dicon';
        $container = S2ContainerFactory::create($diconFile);
        $this->assertType('S2Container',$container);
        $container = S2ContainerFactory::create($diconFile);
        $this->assertType('S2Container',$container);
    }

    function testS2ContainerBuilderInterface() {
        S2ContainerFactory::$BUILDERS['xxx'] = 'B_S2ContainerFactory';
        try {
        	S2ContainerFactory::create('hoge.xxx');
        } catch (Exception $e) {
            $this->assertType('S2Container_S2RuntimeException',$e);
            print "{$e->getMessage()} \n";
        }
        unset(S2ContainerFactory::$BUILDERS['xxx']);
    }

    function testExtension() {
        try {
            S2ContainerFactory::create('hoge.xxx');
        } catch (Exception $e) {
            $this->assertType('S2Container_S2RuntimeException',$e);
            print "{$e->getMessage()} \n";
        }
    }

    function testEnvDicon(){
        define('S2CONTAINER_PHP5_ENV', 'mock');
        try {
            //     exist : testEnvDiconA.dicon
            // not exist : testEnvDiconA_mock.dicon
            $diconFile = $this->diconDir . '/testEnvDiconA.dicon';
            $container = S2ContainerFactory::create($diconFile);
            $this->assertType('S2Container', $container);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        // exist : testEnvDiconB.dicon
        // exist : testEnvDiconB_mock.dicon
        $diconFile = $this->diconDir . '/testEnvDiconB.dicon';
        $container = S2ContainerFactory::create($diconFile);
        $this->assertEquals($container->getPath(), $this->diconDir . DIRECTORY_SEPARATOR . 'testEnvDiconB_mock.dicon');

        // exist : testEnvDiconC.xml.dicon
        // exist : testEnvDiconC_mock.xml.dicon
        $diconFile = $this->diconDir . '/testEnvDiconC.xml.dicon';
        $container = S2ContainerFactory::create($diconFile);
        $this->assertEquals($container->getPath(), $this->diconDir . DIRECTORY_SEPARATOR . 'testEnvDiconC_mock.xml.dicon');

        // exist : testEnvDiconD.dicon
        // exist : testEnvDiconD_mock.xml.dicon
        $diconFile = $this->diconDir . '/testEnvDiconD.dicon';
        $container = S2ContainerFactory::create($diconFile);
        $this->assertEquals($container->getPath(), $this->diconDir . '/testEnvDiconD.dicon');
    }
}

class A_S2ContainerFactory{}
class B_S2ContainerFactory{}
?>
