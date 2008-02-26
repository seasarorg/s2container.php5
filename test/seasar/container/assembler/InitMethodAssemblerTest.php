<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
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
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar::container::assembler;
class InitMethodAssemblerTest extends ::PHPUnit_Framework_TestCase {

    public function testInitMethodAssemble() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::A_InitMethodAssemblerTest');
        $container->register($componentDef);
        $methodDef = new seasar::container::impl::InitMethodDef('hoge');
        $componentDef->addInitMethodDef($methodDef);
        $component = new seasar::container::assembler::A_InitMethodAssemblerTest;
        $assembler = new InitMethodAssembler($componentDef);
        $assembler->assemble($component);
    }

    public function testManualArgAssemble() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::A_InitMethodAssemblerTest');
        $container->register($componentDef);

        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::B_InitMethodAssemblerTest');
        $container->register($componentDef);

        $methodDef = new seasar::container::impl::InitMethodDef('hoge');
        $componentDef->addInitMethodDef($methodDef);
        $argDef = new seasar::container::impl::ArgDef;
        $argDef->setChildComponentDef($container->getComponentDef(__NAMESPACE__ . '::A_InitMethodAssemblerTest'));
        $methodDef->addArgDef($argDef);

        $methodDef = new seasar::container::impl::InitMethodDef('huga');
        $componentDef->addInitMethodDef($methodDef);
        $argDef = new seasar::container::impl::ArgDef(2007);
        $methodDef->addArgDef($argDef);

        $component = new seasar::container::assembler::B_InitMethodAssemblerTest;
        $assembler = new InitMethodAssembler($componentDef);
        $assembler->assemble($component);

        $this->assertTrue($component->a instanceof A_InitMethodAssemblerTest);
        $this->assertEquals($component->b, 2007);
    }

    public function testManualArgsAssemble() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::A_InitMethodAssemblerTest');
        $container->register($componentDef);

        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::B_InitMethodAssemblerTest');
        $container->register($componentDef);

        $methodDef = new seasar::container::impl::InitMethodDef('foo');
        $componentDef->addInitMethodDef($methodDef);
        $argDef = new seasar::container::impl::ArgDef;
        $argDef->setChildComponentDef($container->getComponentDef(__NAMESPACE__ . '::A_InitMethodAssemblerTest'));
        $methodDef->addArgDef($argDef);
        $argDef = new seasar::container::impl::ArgDef(2007);
        $methodDef->addArgDef($argDef);

        $component = new seasar::container::assembler::B_InitMethodAssemblerTest;
        $assembler = new InitMethodAssembler($componentDef);
        $assembler->assemble($component);

        $this->assertTrue($component->a instanceof A_InitMethodAssemblerTest);
        $this->assertEquals($component->b, 2007);
    }

    public function testAutoArgAssemble() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::A_InitMethodAssemblerTest');
        $container->register($componentDef);

        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::B_InitMethodAssemblerTest');
        $container->register($componentDef);

        $methodDef = new seasar::container::impl::InitMethodDef('hoge');
        $componentDef->addInitMethodDef($methodDef);

        $component = new seasar::container::assembler::B_InitMethodAssemblerTest;
        $assembler = new InitMethodAssembler($componentDef);
        $assembler->assemble($component);

        $this->assertTrue($component->a instanceof A_InitMethodAssemblerTest);
    }

    public function testExpressionAssemble() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::B_InitMethodAssemblerTest');
        $container->register($componentDef);

        $methodDef = new seasar::container::impl::InitMethodDef();
        $componentDef->addInitMethodDef($methodDef);
        $methodDef->setExpression('$component->a = "abcd"; $component->b = 2007;');
        $component = new seasar::container::assembler::B_InitMethodAssemblerTest;
        $assembler = new InitMethodAssembler($componentDef);
        $assembler->assemble($component);

        $this->assertEquals($component->a, 'abcd');
        $this->assertEquals($component->b, 2007);
    }

    public function testManualArrayAssemble() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::E_InitMethodAssemblerTest', 'e');
        $container->register($componentDef);
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::E_InitMethodAssemblerTest', 'e');
        $container->register($componentDef);

        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::D_InitMethodAssemblerTest');
        $container->register($componentDef);

        $methodDef = new seasar::container::impl::InitMethodDef('foo');
        $componentDef->addInitMethodDef($methodDef);
        $argDef = new seasar::container::impl::ArgDef;
        $argDef->setChildComponentDef($container->getComponentDef('e'));
        $methodDef->addArgDef($argDef);
        $argDef = new seasar::container::impl::ArgDef(2007);
        $methodDef->addArgDef($argDef);

        $component = new seasar::container::assembler::D_InitMethodAssemblerTest;
        $assembler = new InitMethodAssembler($componentDef);
        $assembler->assemble($component);

        $this->assertTrue(is_array($component->e));
        $this->assertTrue(count($component->e) == 2);
        $this->assertTrue(get_class($component->e[1]) == __NAMESPACE__ . '::E_InitMethodAssemblerTest');
        $this->assertEquals($component->a, 2007);
    }

    public function testManualOneArrayAssemble() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::E_InitMethodAssemblerTest', 'e');
        $container->register($componentDef);

        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::D_InitMethodAssemblerTest');
        $container->register($componentDef);

        $methodDef = new seasar::container::impl::InitMethodDef('foo');
        $componentDef->addInitMethodDef($methodDef);
        $argDef = new seasar::container::impl::ArgDef;
        $argDef->setChildComponentDef($container->getComponentDef('e'));
        $methodDef->addArgDef($argDef);
        $argDef = new seasar::container::impl::ArgDef(2007);
        $methodDef->addArgDef($argDef);

        $component = new seasar::container::assembler::D_InitMethodAssemblerTest;
        $assembler = new InitMethodAssembler($componentDef);
        $assembler->assemble($component);

        $this->assertTrue(is_array($component->e));
        $this->assertTrue(count($component->e) == 1);
        $this->assertTrue(get_class($component->e[0]) == __NAMESPACE__ . '::E_InitMethodAssemblerTest');
        $this->assertEquals($component->a, 2007);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_InitMethodAssemblerTest {
    public function hoge(){
        print __METHOD__ . ' called.' . PHP_EOL;
    }
}

class B_InitMethodAssemblerTest {
    public $a;
    public $b;

    public function hoge(A_InitMethodAssemblerTest $a) {
        $this->a = $a;
    }
    public function huga($b) {
        $this->b = $b;
    }

    public function foo(A_InitMethodAssemblerTest $a, $b) {
        $this->a = $a;
        $this->b = $b;
    }
}

class C_InitMethodAssemblerTest {
    public $a = 's2binding seasar::container::assembler::A_InitMethodAssemblerTest';
}

class D_InitMethodAssemblerTest {
    public $a = null;
    public $e = null;
    public function foo(array $e, $a) {
        $this->a = $a;
        $this->e = $e;
    }
}

class E_InitMethodAssemblerTest {}

