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
class AutoConstructorAssemblerTest extends \PHPUnit_Framework_TestCase {

    public function testAssembleWithoutArgs() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_AutoConstructorAssemblerTest');
        $assembler = new AutoConstructorAssembler($componentDef);
        $this->assertTrue($assembler->assemble() instanceof \seasar\container\assembler\A_AutoConstructorAssemblerTest);
    }

    public function testAssembleWithArgs() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_AutoConstructorAssemblerTest');
        $container->register($componentDef);
        $assembler = new AutoConstructorAssembler($componentDef);
        $componentDef->addArgDef(new \seasar\container\impl\ArgDef(2007));
        try {
            $assembler->assemble();
            $this->fail();
        } catch (\seasar\container\exception\IllegalConstructorRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        }

        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\B_AutoConstructorAssemblerTest');
        $container->register($componentDef);
        $assembler = new AutoConstructorAssembler($componentDef);
        $componentDef->addArgDef(new \seasar\container\impl\ArgDef('huga'));
        $componentDef->addArgDef(new \seasar\container\impl\ArgDef(2007));
        
        $component = $assembler->assemble();
        $this->assertEquals($component->getName(), 'huga');
        $this->assertEquals($component->getYear(), 2007);
    }

    public function testAssembleAuto() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDefC = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\C_AutoConstructorAssemblerTest');
        $componentDefD = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_AutoConstructorAssemblerTest');
        $container->register($componentDefC);
        $container->register($componentDefD);
        $assembler = new AutoConstructorAssembler($componentDefC);

        $component = $assembler->assemble();
        $this->assertTrue($component->getD() instanceof \seasar\container\assembler\D_AutoConstructorAssemblerTest);
    }

    public function testTooManyArrayInjection() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDefD1 = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_AutoConstructorAssemblerTest', 'd');
        $container->register($componentDefD1);
        $componentDefD2 = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_AutoConstructorAssemblerTest', 'd');
        $container->register($componentDefD2);

        $componentDefE = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\E_AutoConstructorAssemblerTest', 'e');
        $argDef = new \seasar\container\impl\ArgDef();
        $argDef->setChildComponentDef($container->getComponentDef('d'));
        $componentDefE->addArgDef($argDef);
        $container->register($componentDefE);
        $assembler = new AutoConstructorAssembler($componentDefE);

        $component = $assembler->assemble();
        $this->assertTrue(is_array($component->getD()));
        $this->assertTrue(count($component->getD()) === 2);
    }

    public function testAssembleMix() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\F_AutoConstructorAssemblerTest');
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_AutoConstructorAssemblerTest'));
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_AutoConstructorAssemblerTest'));
        $container->register($componentDef);
        $assembler = new AutoConstructorAssembler($componentDef);

        $component = $assembler->assemble();
        $this->assertTrue($component->getA() instanceof \seasar\container\assembler\A_AutoConstructorAssemblerTest);
        $this->assertTrue($component->getB() !== 2007); // this is not optional. can not get default value.
        $this->assertTrue($component->getC() === null);
        $this->assertTrue($component->getD() instanceof \seasar\container\assembler\D_AutoConstructorAssemblerTest);
    }

    public function testAssembleMix2() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\G_AutoConstructorAssemblerTest');
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_AutoConstructorAssemblerTest'));
        $container->register(new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_AutoConstructorAssemblerTest'));
        $container->register($componentDef);
        $assembler = new AutoConstructorAssembler($componentDef);

        $component = $assembler->assemble();
        $this->assertTrue($component->getA() instanceof \seasar\container\assembler\A_AutoConstructorAssemblerTest);
        $this->assertTrue($component->getB() instanceof \seasar\container\assembler\D_AutoConstructorAssemblerTest);
        $this->assertTrue($component->getC() === 2007); // this is optional. can not get default value.
        $this->assertTrue($component->getD() === 'hoge');
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_AutoConstructorAssemblerTest {}

class B_AutoConstructorAssemblerTest {
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

class C_AutoConstructorAssemblerTest {
    private $d;
    public function __construct(\seasar\container\assembler\D_AutoConstructorAssemblerTest $d) {
        $this->d = $d;
    }
    public function getD() {
        return $this->d;
    }
}

class D_AutoConstructorAssemblerTest {}

class E_AutoConstructorAssemblerTest {
    public function __construct(array $d) {
        $this->d = $d;
    }
    public function getD() {
        return $this->d;
    }
}

class F_AutoConstructorAssemblerTest {
    private $a = null;
    private $b = null;
    private $c = null;
    private $d = null;
    public function __construct(A_AutoConstructorAssemblerTest $a, $b = 2007, $c = null, D_AutoConstructorAssemblerTest $d) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
    }
    public function getA() {
        return $this->a;
    }

    public function getB() {
        return $this->b;
    }

    public function getC() {
        return $this->c;
    }

    public function getD() {
        return $this->d;
    }
}

class G_AutoConstructorAssemblerTest {
    private $a = null;
    private $b = null;
    private $c = null;
    private $d = null;
    public function __construct(A_AutoConstructorAssemblerTest $a, D_AutoConstructorAssemblerTest $b, $c = 2007, $d = 'hoge') {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
    }
    public function getA() {
        return $this->a;
    }

    public function getB() {
        return $this->b;
    }

    public function getC() {
        return $this->c;
    }

    public function getD() {
        return $this->d;
    }
}
