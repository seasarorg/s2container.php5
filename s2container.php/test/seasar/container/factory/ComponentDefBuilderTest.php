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
 * @package   seasar.container.factory
 * @author    klove
 */
namespace seasar\container\factory;
class ComponentDefBuilderTest extends \PHPUnit_Framework_TestCase {

    public function testCreate() {
        $container = new \seasar\container\impl\S2ContainerImpl;
        $cd = ComponentDefBuilder::create($container, new \ReflectionClass(__NAMESPACE__ . '\A'));
        $this->assertTrue($cd instanceof \seasar\container\ComponentDef);
    }

    public function testNotAbailable() {
        $container = new \seasar\container\impl\S2ContainerImpl;
        $cd = ComponentDefBuilder::create($container, new \ReflectionClass(__NAMESPACE__ . '\B'));
        $this->assertEquals($cd, null);
    }

    public function testDI() {
        $container = new \seasar\container\impl\S2ContainerImpl;
        $this->assertTrue($container->hasComponentDef(__NAMESPACE__ . '\D'));
        $this->assertTrue($container->hasComponentDef('d'));
        $c = $container->getComponent(__NAMESPACE__ . '\C');
        $this->assertTrue($c->a instanceof A);
        $this->assertTrue($c->d instanceof D);

        $container = new \seasar\container\impl\S2ContainerImpl;
        $e = $container->getComponent(__NAMESPACE__ . '\E');
        $this->assertTrue($e->a1 === null);
        $this->assertTrue($e->a2 instanceof A);
    }

    public function testAspect() {
        $container = new \seasar\container\impl\S2ContainerImpl;
        $f = $container->getComponent(__NAMESPACE__ . '\F');
        $this->assertEquals(1, preg_match('/Enhanced/' , get_class($f)));
        $f->foo();
    }

    public function testUserDefined() {
        $container = new \seasar\container\impl\S2ContainerImpl;
        $g = $container->getComponent(__NAMESPACE__ . '\G');
        $this->assertEquals(null, $g->pdo);
    }

    public function testAbstract() {
        $container = new \seasar\container\impl\S2ContainerImpl;
        try {
            $container->getComponent(__NAMESPACE__ . '\H');
            $this->fail();
        } catch(\seasar\container\exception\ComponentNotFoundRuntimeException $e) {
            $this->assertTrue(true);
        }

        $container = new \seasar\container\impl\S2ContainerImpl;
        $i = $container->getComponent(__NAMESPACE__ . '\I');
        $this->assertTrue($i instanceof I);
    }

    public function testInterface() {
        $container = new \seasar\container\impl\S2ContainerImpl;
        try {
            $container->getComponent(__NAMESPACE__ . '\J');
            $this->fail();
        } catch(\seasar\container\exception\ComponentNotFoundRuntimeException $e) {
            $this->assertTrue(true);
        }

        $container = new \seasar\container\impl\S2ContainerImpl;
        $k = $container->getComponent(__NAMESPACE__ . '\K');
        $this->assertTrue($k instanceof K);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

/**
 * @S2Component('name' => 'a')
 */
class A{}

/**
 * @S2Component('available' => false)
 */
class B{}

class C {
    public $a = null;
    public $d = null;

    public function setA(A $a) {$this->a = $a;}

    /**
     * @S2Binding("d")
     */
    public function setD($d) {$this->d = $d;}
}

/**
 * @S2Component('name' => 'd')
 */
class D{}

class E {
    public $a1 = null;
    public $a2 = 's2binding \seasar\container\factory\A';
}

/**
 * @S2Aspect('interceptor' => 'new \seasar\aop\interceptor\TraceInterceptor')
 */
class F {
    public function foo() {
        echo 'foo called.' . PHP_EOL;
    }
}


class G {
    public $pdo = null;
    public function setPdo(\Pdo $pdo) {
        $this->pdo = $pdo;
    }
}

abstract class H {}

/**
 * @S2Aspect('interceptor' => 'new \seasar\aop\interceptor\TraceInterceptor')
 */
abstract class I {}

interface J {}

interface K {
    /**
     * @S2Aspect('interceptor' => 'new \seasar\aop\interceptor\TraceInterceptor')
     */
    public function foo();
}
