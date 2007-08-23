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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * @package org.seasar.framework.aop.intertype
 * @author nowel
 */
class S2Container_AbstractInterTypeTest extends PHPUnit_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp() {
        $this->diconDir = dirname(__FILE__) . '/dicon/' . __CLASS__;
        if (!defined('DICON_DIR_S2Container_AbstractInterTypeTest')){
            define('DICON_DIR_S2Container_AbstractInterTypeTest', $this->diconDir);
        }
        require_once 'spyc.php';
        $this->container = S2ContainerFactory::create($this->diconDir . '/AbstractInterTypeTest.yml');
    }

    public function test() {
        $o = $this->container->getComponent("a");
        $o->run();
        $this->assertEquals("Hoge1", $o->toString());

        $refClass = new ReflectionClass($o);
        $param = $refClass->getMethod("setFoo")->getParameters();
        $this->assertSame($param[0]->getClass()->getName(), "S2Container_AbstractInterTypeTest_Foo");
    }
}

interface S2Container_AbstractInterTypeTest_IX {
}

interface S2Container_AbstractInterTypeTest_Foo {
}

class S2Container_AbstractInterTypeTest_FooImpl
implements S2Container_AbstractInterTypeTest_Foo {
}

class S2Container_AbstractInterTypeTestTestInterType extends S2Container_AbstractInterType {

    public function introduce(ReflectionClass $targetClass, $enhancedClass) {
        parent::introduce($targetClass, $enhancedClass);
        $this->addInterface("S2Container_AbstractInterTypeTest_IX");

        $this->addProperty(array(self::PUBLIC_), "hoge");
        $this->addMethod(array(self::PUBLIC_), 'setHoge', '($value){$this->hoge = $value;}');
        $this->addMethod(array(self::PUBLIC_), "getHoge", '(){return $this->hoge;}');

        $this->addMethod(array(self::PUBLIC_), "run", '() {$this->setHoge("Hoge"); $this->getHoge();}');
        $this->addMethod(array(self::PUBLIC_), "toString",'(){ return $this->getHoge() . "1";}');

        $this->addProperty(array(self::PUBLIC_), "foo");
        $this->addMethod(array(self::PUBLIC_), "setFoo", '(S2Container_AbstractInterTypeTest_Foo $v){$this->foo = $v;}');
        $this->addMethod(array(self::PUBLIC_), "getFoo", "(){return \$this->foo;}");
    }
}
