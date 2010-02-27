<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2010 the Seasar Foundation and the Others.            |
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
/**
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar\container\impl;
class ComponentDefImplTest extends \PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $def = new ComponentDefImpl(__NAMESPACE__ . '\A_ComponentDefTest');
        $this->assertTrue($def instanceof \seasar\container\ComponentDef);
        $this->assertTrue($def->getComponentClass() instanceof \ReflectionClass);
        $this->assertEquals($def->getComponentClass()->getName(), __NAMESPACE__ . '\A_ComponentDefTest');
        $this->assertTrue(is_null($def->getComponentName()));
    }

    public function testSetContainer() {
        $container = new S2ContainerImpl();
        $def = new ComponentDefImpl('\seasar\container\impl\A_ComponentDefTest');
        $argDef = new ArgDef(100);
        $def->addArgDef($argDef);
        $def->setContainer($container);
        $this->assertEquals($argDef->getContainer(), $container);
    }

    public function testArgDef() {
        $container = new S2ContainerImpl();
        $def = new ComponentDefImpl('\seasar\container\impl\A_ComponentDefTest');
        $argDef = new ArgDef(100);
        $def->addArgDef($argDef);
        $argDef = new ArgDef(200);
        $def->addArgDef($argDef);
        $this->assertEquals(2, $def->getArgDefSize());
        $this->assertTrue(is_array($def->getArgDefs()));
        $this->assertEquals(2, count($def->getArgDefs()));
        $this->assertTrue($def->getArgDef(0) instanceof ArgDef);
        $this->assertTrue($def->getArgDef(1) instanceof ArgDef);
        try {
            $def->getArgDef(2);
            $this->fail();
        } catch(\OutOfRangeException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function testPropertyDef() {
        $container = new S2ContainerImpl();
        $def = new ComponentDefImpl('\seasar\container\impl\A_ComponentDefTest');
        $propertyDef = new PropertyDef('a');
        $def->addPropertyDef($propertyDef);
        $propertyDef = new PropertyDef('b');
        $def->addPropertyDef($propertyDef);
        $this->assertEquals(2, $def->getPropertyDefSize());
        $this->assertTrue(is_array($def->getPropertyDefs()));
        $this->assertEquals(2, count($def->getPropertyDefs()));
        $this->assertTrue($def->getPropertyDef(0) instanceof PropertyDef);
        $this->assertTrue($def->getPropertyDef('a') instanceof PropertyDef);
        $this->assertTrue($def->getPropertyDef('b') instanceof PropertyDef);
        try {
            $def->getPropertyDef(2);
            $this->fail();
        } catch(\OutOfRangeException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function testAspectDef() {
        $container = new S2ContainerImpl();
        $def = new ComponentDefImpl('\seasar\container\impl\A_ComponentDefTest');
        $aspectDef = new AspectDef();
        $def->addAspectDef($aspectDef);
        $aspectDef = new AspectDef();
        $def->addAspectDef($aspectDef);
        $this->assertEquals(2, $def->getAspectDefSize());
        $this->assertTrue(is_array($def->getAspectDefs()));
        $this->assertEquals(2, count($def->getAspectDefs()));
        $this->assertTrue($def->getAspectDef(0) instanceof AspectDef);
        $this->assertTrue($def->getAspectDef(1) instanceof AspectDef);
        try {
            $def->getAspectDef(2);
            $this->fail();
        } catch(\OutOfRangeException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function testMetaDef() {
        $container = new S2ContainerImpl();
        $def = new ComponentDefImpl('\seasar\container\impl\A_ComponentDefTest');
        $metaDef = new MetaDef('a');
        $def->addMetaDef($metaDef);
        $metaDef = new MetaDef('b');
        $def->addMetaDef($metaDef);
        $this->assertEquals(2, $def->getMetaDefSize());
        $this->assertTrue(is_array($def->getMetaDefs('a')));
        $this->assertEquals(1, count($def->getMetaDefs('a')));
        $this->assertTrue($def->getMetaDef(0) instanceof MetaDef);
        $this->assertTrue($def->getMetaDef(1) instanceof MetaDef);
        try {
            $def->getMetaDef(2);
            $this->fail();
        } catch(\OutOfRangeException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }


    public function testInitMethodDef() {
        $container = new S2ContainerImpl();
        $def = new ComponentDefImpl('\seasar\container\impl\A_ComponentDefTest');
        $initMethodDef = new InitMethodDef('a');
        $def->addInitMethodDef($initMethodDef);
        $initMethodDef = new InitMethodDef('b');
        $def->addInitMethodDef($initMethodDef);
        $this->assertEquals(2, $def->getInitMethodDefSize());
        $this->assertTrue(is_array($def->getInitMethodDefs()));
        $this->assertEquals(2, count($def->getInitMethodDefs()));
        $this->assertTrue($def->getInitMethodDef(0) instanceof InitMethodDef);
        $this->assertTrue($def->getInitMethodDef(1) instanceof InitMethodDef);
        try {
            $def->getInitMethodDef(2);
            $this->fail();
        } catch(\OutOfRangeException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_ComponentDefTest {}

