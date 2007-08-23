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
abstract class S2Container_AbstractPropertyInterTypeTest extends PHPUnit_Framework_TestCase {

    private $target;
    private $targetClass;
    private $target2;
    private $targetClass2;

    abstract protected function getPath();

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp() {
        parent::setUp();

        $container = S2ContainerFactory::create($this->getPath());

        $this->target = $container->getComponent("target");
        $this->targetClass = new ReflectionClass($this->target);
        $this->target2 = $container->getComponent("target2");
        $this->targetClass2 = new ReflectionClass($this->target2);
    }

    public function testSetterGetter() {
        // getter test
        $this->assertEquals($this->getIntField("getIntReadField"), 123);

        // setter test
        $this->setIntField("setIntWriteField", 456);
        $this->assertEquals($this->target->getIntWriteField(), 456);

        // setter/getter test
        $this->setIntField("setIntReadWriteField", 789);
        $this->assertEquals($this->getIntField("getIntReadWriteField"), 789);
    }

    public function testFieldType() {
        $testValue1 = new ArrayObject();
        $this->setField("setObjectField", $testValue1);
        $this->assertEquals($this->getField("getObjectField"), $testValue1);

        $testValue2 = array("apple", "orange", "lemon");
        $this->setField("setStringArrayField", $testValue2);
        $this->assertEquals($this->getField("getStringArrayField"), $testValue2);

        $testValue3 = array(array(123, 456, 789), array(123, 456, 789));
        $this->setField("setLongArrayField", $testValue3);
        $this->assertEquals($this->getField("getLongArrayField"), $testValue3);
    }

    public function testModifier() {
        // Does public field's getter exist?
        $this->assertMethodExists("getPublicField");

        // Does public field's setter exist?
        $this->assertMethodExists("setPublicField");

        // Does protected field's getter exist?
        $this->assertMethodExists("getProtectedField");

        // Does protected field's setter exist?
        $this->assertMethodExists("setProtectedField");

        // Does private field's getter exist?
        $this->assertMethodNotExists("getPrivateField");

        // Does private field's setter exist?
        $this->assertMethodNotExists("setPrivateField");

        // Does none annotated field's getter exist?
        $this->assertMethodNotExists("getIntNoneField");

        // Does none annotated field's setter exist?
        $this->assertMethodNotExists("setIntNoneField");

        // Does none annotated field's getter exist?
        $this->assertMethodNotExists("getNonAnnotatedField");

        // Does none annotated field's setter exist?
        $this->assertMethodNotExists("setNonAnnotatedField");
    }

    public function testClassAnnotated() {
        // Does default (not annotated) field's getter exist?
        $this->assertMethodExists($this->targetClass2, "getDefaultField");

        // Does default (not annotated) field's setter exist?
        $this->assertMethodExists($this->targetClass2, "setDefaultField");

        // Does read only (override) field's getter exist?
        $this->assertMethodExists($this->targetClass2, "getReadField");

        // Does read only (override) field's setter exist?
        $this->assertMethodNotExists($this->targetClass2, "setReadField");

        // Does write only (override) field's getter exist?
        $this->assertMethodNotExists($this->targetClass2, "getWriteField");

        // Does read only (override) field's setter exist?
        $this->assertMethodExists($this->targetClass2, "setWriteField");

        // Does read-write (override) field's getter exist?
        $this->assertMethodExists($this->targetClass2, "getReadWriteField");

        // Does read-write (override) field's setter exist?
        $this->assertMethodExists($this->targetClass2, "setReadWriteField");

        // Does none (override) field's getter exist?
        $this->assertMethodNotExists($this->targetClass2, "getNoneField");

        // Does none (override) field's setter exist?
        $this->assertMethodNotExists($this->targetClass2, "setNoneField");
    }

    public function testHasMethod() {
        $this->assertMethodNotExists($this->targetClass2, "getHasGetter");
        $this->assertMethodExists($this->targetClass2, "setHasGetter");

        $this->assertMethodExists($this->targetClass2, "getHasSetter");
        $this->assertMethodNotExists($this->targetClass2, "setHasSetter");

        $this->assertMethodNotExists($this->targetClass2, "getHasGetterSetter");
        $this->assertMethodNotExists($this->targetClass2, "setHasGetterSetter");
    }

    private function setIntField($methodName, $param = null) {
        $setter = S2Container_ClassUtil::getMethod($this->targetClass, $methodName);
        S2Container_MethodUtil::invoke($setter, $this->target,array($param));
    }

    private function getIntField($methodName) {
        $getter = S2Container_ClassUtil::getMethod($this->targetClass, $methodName);
        $value = S2Container_MethodUtil::invoke($getter, $this->target);
        return (int)$value;
    }

    private function setField($methodName, $param = null) {
        $setter = S2Container_ClassUtil::getMethod($this->targetClass, $methodName);
        S2Container_MethodUtil::invoke($setter, $this->target, $param);
    }

    private function getField($methodName) {
        $getter = S2Container_ClassUtil::getMethod($this->targetClass, $methodName);
        $value = S2Container_MethodUtil::invoke($getter, $this->target);
        return $value;
    }

    private function assertMethodExists($methodName) {
        return $this->targetClass->hasMethod($methodName);
    }

    private function assertMethodNotExists($methodName) {
        try {
            $this->targetClass->getMethod($methodName);
            $this->fail("The method " + $methodName + " exsts.");
        } catch (S2Container_NoSuchMethodRuntimeException $e) {
        }
    }
}
