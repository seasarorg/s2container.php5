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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * @package org.seasar.framework.aop.intertype
 * @author nowel
 */
class S2Container_InterTypeChainTest extends PHPUnit2_Framework_TestCase {
    
    private $conteiner;
    
    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp() {
        $this->diconDir = dirname(__FILE__) . '/dicon/' . __CLASS__;
        if (!defined('DICON_DIR_S2Container_InterTypeChainTest')){
            define('DICON_DIR_S2Container_InterTypeChainTest', $this->diconDir);
        }
        require_once 'spyc.php';
        $this->container = S2ContainerFactory::create($this->diconDir . '/InterTypeChainTest.yml');
    }

    public function test() {
        $component = $this->container->getComponent("test");
        $this->assertTrue($component instanceof S2Container_InterTypeChainTest_IXA);
        $this->assertTrue($component instanceof S2Container_InterTypeChainTest_IXB);
    }
}

interface S2Container_InterTypeChainTest_IXA {
}

interface S2Container_InterTypeChainTest_IXB {
}

class S2Container_InterTypeChainTest_InterType1
    extends S2Container_AbstractInterType {
    public function introduce(ReflectionClass $targetClass, $enhancedClassName) {
        parent::introduce($targetClass, $enhancedClassName);
        $this->addInterface("S2Container_InterTypeChainTest_IXA");
    }
}

class S2Container_InterTypeChainTest_InterType2
    extends S2Container_AbstractInterType {
    public function introduce(ReflectionClass $targetClass, $enhancedClassName) {
        parent::introduce($targetClass, $enhancedClassName);
        $this->addInterface("S2Container_InterTypeChainTest_IXB");
    }
}

class S2Container_InterTypeChainTest_TestClass {
    public function run() {
    }
}