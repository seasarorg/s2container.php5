<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2009 the Seasar Foundation and the Others.            |
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
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
class ManualPropertyAssemblerTest extends \PHPUnit_Framework_TestCase {

    public function testAssembleWithoutProperty() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_ManualPropertyAssemblerTest');
        $component = new \seasar\container\assembler\A_ManualPropertyAssemblerTest;
        $assembler = new ManualPropertyAssembler($componentDef);
        $assembler->assemble($component);
    }

    public function testManualAssembleWithSetterMethod() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\B_ManualPropertyAssemblerTest');
        $componentDef->addPropertyDef(new \seasar\container\impl\PropertyDef('name', 'hoge'));
        $component = new \seasar\container\assembler\B_ManualPropertyAssemblerTest;
        $assembler = new ManualPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertEquals($component->getName(), 'hoge');
    }

    public function testManualAssembleWithPublicProperty() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\C_ManualPropertyAssemblerTest');
        $componentDef->addPropertyDef(new \seasar\container\impl\PropertyDef('name', 'hoge'));
        $component = new \seasar\container\assembler\C_ManualPropertyAssemblerTest;
        $assembler = new ManualPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertEquals($component->name, 'hoge');
    }

    public function testManualAssembleWithPublicPropertyArray() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_ManualPropertyAssemblerTest', 'd');
        $container->register($componentDef);
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_ManualPropertyAssemblerTest', 'hoge'));
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_ManualPropertyAssemblerTest', 'hoge'));
        $propDef = new \seasar\container\impl\PropertyDef('name');
        $propDef->setChildComponentDef($container->getComponentDef('hoge'));
        $componentDef->addPropertyDef($propDef);
        $component = new \seasar\container\assembler\D_ManualPropertyAssemblerTest;
        $assembler = new ManualPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertEquals(count($component->name), 2);
    }

    public function testManualAssembleWithSetterMethodArray() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_ManualPropertyAssemblerTest', 'd');
        $container->register($componentDef);
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_ManualPropertyAssemblerTest', 'hoge'));
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_ManualPropertyAssemblerTest', 'hoge'));
        $propDef = new \seasar\container\impl\PropertyDef('year');
        $propDef->setChildComponentDef($container->getComponentDef('hoge'));
        $componentDef->addPropertyDef($propDef);
        $component = new \seasar\container\assembler\D_ManualPropertyAssemblerTest;
        $assembler = new ManualPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertEquals(count($component->getYear()), 2);
    }

    public function testManualNoProperty() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_ManualPropertyAssemblerTest', 'e');
        $container->register($componentDef);
        $propDef = new \seasar\container\impl\PropertyDef('year');
        $componentDef->addPropertyDef($propDef);
        $component = new \seasar\container\assembler\E_ManualPropertyAssemblerTest;
        $assembler = new ManualPropertyAssembler($componentDef);
        try {
            $assembler->assemble($component);
            $this->fail();
        } catch(\seasar\container\exception\PropertyNotFoundRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function testManualAssembleWithPublicPropertyArrayOne() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_ManualPropertyAssemblerTest', 'd');
        $container->register($componentDef);
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_ManualPropertyAssemblerTest', 'hoge'));
        $propDef = new \seasar\container\impl\PropertyDef('name');
        $propDef->setChildComponentDef($container->getComponentDef('hoge'));
        $componentDef->addPropertyDef($propDef);
        $component = new \seasar\container\assembler\D_ManualPropertyAssemblerTest;
        $assembler = new ManualPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertTrue(is_array($component->name));
        $this->assertEquals(count($component->name), 1);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_ManualPropertyAssemblerTest {}

class B_ManualPropertyAssemblerTest {
    private $name;
    
    public function setName($name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
}

class C_ManualPropertyAssemblerTest {
    public $name;
}

class D_ManualPropertyAssemblerTest {
    public $name = 'S2Binding hoge[]';

    private $year;
    public function setYear(array $year) {
        $this->year = $year;
    }
    public function getYear() {
        return $this->year;
    }
}

class E_ManualPropertyAssemblerTest {}

