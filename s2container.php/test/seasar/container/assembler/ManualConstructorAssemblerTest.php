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
class ManualConstructorAssemblerTest extends ::PHPUnit_Framework_TestCase {

    public function testAssembleWithoutArgs() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::A_ManualConstructorAssemblerTest');
        $assembler = new ManualConstructorAssembler($componentDef);
        $this->assertTrue($assembler->assemble() instanceof seasar::container::assembler::A_ManualConstructorAssemblerTest);
    }

    public function testAssembleWithArgs() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::A_ManualConstructorAssemblerTest');
        $container->register($componentDef);
        $assembler = new ManualConstructorAssembler($componentDef);
        $componentDef->addArgDef(new seasar::container::impl::ArgDef(2007));
        try {
            $assembler->assemble();
            $this->fail();
        } catch (seasar::container::exception::IllegalConstructorRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        }

        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDef = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::B_ManualConstructorAssemblerTest');
        $container->register($componentDef);
        $assembler = new ManualConstructorAssembler($componentDef);
        $componentDef->addArgDef(new seasar::container::impl::ArgDef('huga'));
        $componentDef->addArgDef(new seasar::container::impl::ArgDef(2007));
        
        $component = $assembler->assemble();
        $this->assertEquals($component->getName(), 'huga');
        $this->assertEquals($component->getYear(), 2007);
    }

    public function testAssembleManual() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDefC = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::C_ManualConstructorAssemblerTest');
        $componentDefD = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::D_ManualConstructorAssemblerTest');
        $container->register($componentDefC);
        $container->register($componentDefD);

        $argDef = new seasar::container::impl::ArgDef();
        $argDef->setChildComponentDef($container->getComponentDef(__NAMESPACE__ . '::D_ManualConstructorAssemblerTest'));
        $componentDefC->addArgDef($argDef);

        $assembler = new ManualConstructorAssembler($componentDefC);

        $component = $assembler->assemble();
        $this->assertTrue($component->getD() instanceof seasar::container::assembler::D_ManualConstructorAssemblerTest);
    }

    public function testTooManyArrayInjection() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDefD1 = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::D_ManualConstructorAssemblerTest', 'd');
        $container->register($componentDefD1);
        $componentDefD2 = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::D_ManualConstructorAssemblerTest', 'd');
        $container->register($componentDefD2);

        $componentDefE = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::E_ManualConstructorAssemblerTest', 'e');
        $argDef = new seasar::container::impl::ArgDef();
        $argDef->setChildComponentDef($container->getComponentDef('d'));
        $componentDefE->addArgDef($argDef);
        $container->register($componentDefE);
        $assembler = new ManualConstructorAssembler($componentDefE);

        $component = $assembler->assemble();
        $this->assertTrue(is_array($component->getD()));
        $this->assertTrue(count($component->getD()) === 2);
    }

    public function testOneArrayInjection() {
        $container = new seasar::container::impl::S2ContainerImpl();
        $componentDefD2 = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::D_ManualConstructorAssemblerTest', 'd');
        $container->register($componentDefD2);

        $componentDefE = new seasar::container::impl::ComponentDefImpl(__NAMESPACE__ . '::E_ManualConstructorAssemblerTest', 'e');
        $argDef = new seasar::container::impl::ArgDef();
        $argDef->setChildComponentDef($container->getComponentDef('d'));
        $componentDefE->addArgDef($argDef);
        $container->register($componentDefE);
        $assembler = new ManualConstructorAssembler($componentDefE);

        $component = $assembler->assemble();
        $this->assertTrue(is_array($component->getD()));
        $this->assertTrue(count($component->getD()) === 1);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_ManualConstructorAssemblerTest {}

class B_ManualConstructorAssemblerTest {
    private $name;
    private $year;
    public function __construct($name, $year) {
        $this->name = $name;
        $this->year = $year;
    }
    public function getName() {
        return $this->name;
    }
    public function getYear() {
        return $this->year;
    }
}

class C_ManualConstructorAssemblerTest {
    private $d;
    public function __construct(seasar::container::assembler::D_ManualConstructorAssemblerTest $d) {
        $this->d = $d;
    }
    public function getD() {
        return $this->d;
    }
}

class D_ManualConstructorAssemblerTest {}

class E_ManualConstructorAssemblerTest {
    public function __construct(array $d) {
        $this->d = $d;
    }
    public function getD() {
        return $this->d;
    }
}
