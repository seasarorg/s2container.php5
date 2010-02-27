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
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
class AutoPropertyAssemblerTest extends \PHPUnit_Framework_TestCase {

    public function testAssembleWithoutProperty() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_AutoPropertyAssemblerTest');
        $component = new \seasar\container\assembler\A_AutoPropertyAssemblerTest;
        $assembler = new AutoPropertyAssembler($componentDef);
        $assembler->assemble($component);
    }

    public function testSetterMethod() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_AutoPropertyAssemblerTest');
        $container->register($componentDef);
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\B_AutoPropertyAssemblerTest');
        $container->register($componentDef);
        $component = new \seasar\container\assembler\B_AutoPropertyAssemblerTest;
        $assembler = new AutoPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertEquals(get_class($component->getA()), __NAMESPACE__ . '\A_AutoPropertyAssemblerTest');
    }

    public function testPublicProperty() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_AutoPropertyAssemblerTest');
        $container->register($componentDef);
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\C_AutoPropertyAssemblerTest');
        $container->register($componentDef);
        $component = new \seasar\container\assembler\C_AutoPropertyAssemblerTest;
        $assembler = new AutoPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertEquals(get_class($component->a), __NAMESPACE__ . '\A_AutoPropertyAssemblerTest');
    }

    public function testPublicPropertyArray() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_AutoPropertyAssemblerTest', 'd');
        $container->register($componentDef);
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_AutoPropertyAssemblerTest', 'hoge'));
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_AutoPropertyAssemblerTest', 'hoge'));
        $component = new \seasar\container\assembler\D_AutoPropertyAssemblerTest;
        $assembler = new AutoPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertEquals(count($component->name), 2);
        $e = $component->name;
        $this->assertEquals(get_class($e[0]), __NAMESPACE__ . '\E_AutoPropertyAssemblerTest');
        $this->assertEquals(get_class($e[1]), __NAMESPACE__ . '\E_AutoPropertyAssemblerTest');
    }

    public function testPublicPropertyArrayOne() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_AutoPropertyAssemblerTest', 'd');
        $container->register($componentDef);
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_AutoPropertyAssemblerTest', 'hoge'));
        $component = new \seasar\container\assembler\D_AutoPropertyAssemblerTest;
        $assembler = new AutoPropertyAssembler($componentDef);
        $assembler->assemble($component);
        $this->assertEquals(count($component->name), 1);
        $e = $component->name;
        $this->assertTrue(is_array($e));
        $this->assertEquals(get_class($e[0]), __NAMESPACE__ . '\E_AutoPropertyAssemblerTest');
    }

    public function testPublicPropertyComponentNotFound() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\F_AutoPropertyAssemblerTest');
        $container->register($componentDef);
        $assembler = new AutoPropertyAssembler($componentDef);
        $component = new \seasar\container\assembler\F_AutoPropertyAssemblerTest;
        $assembler->assemble($component);
    }

    public function testPublicPropertyTooManyRegistration() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\F_AutoPropertyAssemblerTest');
        $container->register($componentDef);
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_AutoPropertyAssemblerTest', 'huga'));
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_AutoPropertyAssemblerTest', 'huga'));
        $assembler = new AutoPropertyAssembler($componentDef);
        $component = new \seasar\container\assembler\F_AutoPropertyAssemblerTest;
        $assembler->assemble($component);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_AutoPropertyAssemblerTest {}

class B_AutoPropertyAssemblerTest {
    private $a;
    
    public function setA(A_AutoPropertyAssemblerTest $a) {
        $this->a = $a;
    }
    public function getA() {
        return $this->a;
    }
}

class C_AutoPropertyAssemblerTest {
    public $a = 's2binding seasar\container\assembler\A_AutoPropertyAssemblerTest';
}

class D_AutoPropertyAssemblerTest {
    public $name = 'S2Binding hoge[]';
}

class E_AutoPropertyAssemblerTest {}

class F_AutoPropertyAssemblerTest {
    public $huga;
}
