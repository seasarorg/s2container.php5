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
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar\container\impl;
class S2ContainerImplTest extends \PHPUnit_Framework_TestCase {

    public function testGetRoot() {
        $container = new S2ContainerImpl();
        $this->assertTrue($container->getRoot() === $container);
    }

    public function testGetComponent() {
        $container = new S2ContainerImpl();
        $this->assertTrue($container->getComponent('\seasar\container\S2Container') === $container);
        $this->assertTrue($container->getComponent('container') === $container);
    }

    public function testRegister() {
        $componentDef = new ComponentDefImpl('\seasar\container\impl\A_S2ContainerImplTest', 'hoge');
        $container = new S2ContainerImpl();
        $container->register($componentDef);
        $hoge = $container->getComponent('hoge');
        $this->assertTrue($hoge instanceof \seasar\container\impl\A_S2ContainerImplTest);

        $container = new S2ContainerImpl();
        $container->register('\seasar\container\impl\A_S2ContainerImplTest', 'hoge');
        $hoge = $container->getComponent('hoge');
        $this->assertTrue($hoge instanceof \seasar\container\impl\A_S2ContainerImplTest);
    }

    public function testFindComponents() {
        $container = new S2ContainerImpl();
        $componentDef = new ComponentDefImpl('\seasar\container\impl\A_S2ContainerImplTest', 'hoge');
        $container->register($componentDef);
        $componentDef = new ComponentDefImpl('\seasar\container\impl\B_S2ContainerImplTest', 'hoge');
        $container->register($componentDef);
        $components = $container->findComponents('hoge');
        $this->assertTrue($components[0] instanceof \seasar\container\impl\A_S2ContainerImplTest);
        $this->assertTrue($components[1] instanceof \seasar\container\impl\B_S2ContainerImplTest);

        $componentDef = new ComponentDefImpl('\seasar\container\impl\C_S2ContainerImplTest', 'hoge');
        $container->register($componentDef);
        $components = $container->findComponents('hoge');
        $this->assertTrue($components[0] instanceof \seasar\container\impl\A_S2ContainerImplTest);
        $this->assertTrue($components[1] instanceof \seasar\container\impl\B_S2ContainerImplTest);
        $this->assertTrue($components[2] instanceof \seasar\container\impl\C_S2ContainerImplTest);
    }

    public function testGetComponentDefSize() {
        $container = new S2ContainerImpl();
        $this->assertEquals($container->getComponentDefSize() , 0);

        $componentDef = new ComponentDefImpl('\seasar\container\impl\A_S2ContainerImplTest', 'a');
        $container->register($componentDef);
        $this->assertEquals($container->getComponentDefSize() , 1);

        $componentDef = new ComponentDefImpl('\seasar\container\impl\B_S2ContainerImplTest', 'b');
        $container->register($componentDef);
        $this->assertEquals($container->getComponentDefSize() , 2);
    }

    public function testDescendant() {
        $root = new S2ContainerImpl();
        $container = new S2ContainerImpl();
        $path = '/path/to/a';
        $container->setPath($path);
        $root->registerDescendant($container);
        $descendant = $root->getDescendant($path);
        $this->assertTrue($container === $descendant);
    }

    public function testChildContainer() {
        $root = new S2ContainerImpl();
        $child = new S2ContainerImpl();
        $root->includeChild($child);
        $child = new S2ContainerImpl();
        $root->includeChild($child);
        $this->assertEquals($root->getChildSize(), 2);

        $child = new S2ContainerImpl();
        $child->setNamespace('a');
        $root->includeChild($child);
        $this->assertEquals($root->getChildSize(), 3);
        $this->assertTrue($root->getChild(2) === $child);
        $this->assertTrue($root->getComponent('a') === $child);
    }

    public function testMetaDef() {
    }

    public function testComponentDefNotFound() {
        $container = new S2ContainerImpl();
        try {
            $container->getComponentDef('xxx');
            $this->fail();
        } catch(\seasar\container\exception\ComponentNotFoundRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function testContainerNotFound() {
        $container = new S2ContainerImpl();
        try {
            $container->getDescendant('/path/to/xxx');
            $this->fail();
        } catch(\seasar\container\exception\ContainerNotRegisteredRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        }

        try {
            $container->getChild(5);
            $this->fail();
        } catch(\seasar\container\exception\ContainerNotRegisteredRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function testComponentNames() {
        $container = new S2ContainerImpl();
        $componentDef = new ComponentDefImpl('\seasar\container\impl\A_S2ContainerImplTest', 'hoge');
        $container->register($componentDef);
        $components = $container->findComponents('hoge');
        $this->assertTrue($container->hasComponentDef('hoge'));
        $this->assertTrue($container->hasComponentDef('A_S2ContainerImplTest'));
        $this->assertTrue($container->hasComponentDef('a_S2ContainerImplTest'));
    }

    public function testPropertyBinding() {
        $container = new S2ContainerImpl();
        $componentDef = new ComponentDefImpl('\seasar\container\impl\A_S2ContainerImplTest');
        $container->register($componentDef);
        $componentDef = new ComponentDefImpl('\seasar\container\impl\D_S2ContainerImplTest', 'd');
        $container->register($componentDef);
        $component = $container->getComponent('d');
        $this->assertTrue($component->a_S2ContainerImplTest instanceof \seasar\container\impl\A_S2ContainerImplTest);
    }

    public function testClassComponentDefBuilder() {
        $container = new S2ContainerImpl();
        $this->assertFalse($container->hasComponentDef('e_s2container'));
        $this->assertTrue($container->hasComponentDef(__NAMESPACE__ . '\E'));
        $this->assertTrue($container->hasComponentDef('e_s2container'));
    }

    /**
     *      a   x
     *     b c y
     *    d e f
     *      g
     */
    public function testParentNamespace() {
        $a = new S2ContainerImpl();
        $a->setNamespace('ns_a');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\A');
        $a->register($cd);

        $b = new S2ContainerImpl();
        $b->setNamespace('ns_b');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\B');
        $b->register($cd);

        $c = new S2ContainerImpl();
        $c->setNamespace('ns_c');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\C');
        $c->register($cd);

        $d = new S2ContainerImpl();
        $d->setNamespace('ns_d');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\D');
        $d->register($cd);

        $e = new S2ContainerImpl();
        $e->setNamespace('ns_e');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\E');
        $e->register($cd);

        $f = new S2ContainerImpl();
        $f->setNamespace('ns_f');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\F');
        $f->register($cd);

        $g = new S2ContainerImpl();
        $g->setNamespace('ns_g');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\G', 'Gg');
        $g->register($cd);

        $x = new S2ContainerImpl();
        $x->setNamespace('ns_x');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\X');
        $x->register($cd);

        $y = new S2ContainerImpl();
        $y->setNamespace('ns_y');
        $cd = new ComponentDefImpl(__NAMESPACE__ . '\Y');
        $y->register($cd);

        $a->includeChild($b);
        $a->includeChild($c);
        $b->includeChild($d);
        $b->includeChild($e);
        $c->includeChild($e);
        $c->includeChild($f);
        $d->includeChild($g);
        $e->includeChild($g);
        $f->includeChild($g);

        $x->includeChild($y);
        $y->includeChild($f);

        $this->assertEquals($a, $g->getRoot());
        $this->assertTrue(in_array($e, $g->getParents(), true));
        $this->assertEquals(3, count($g->getParents()));
        $this->assertTrue($g->hasComponentDef(__NAMESPACE__ . '\A'));
        $this->assertTrue($g->hasComponentDef(__NAMESPACE__ . '\C'));

        $this->assertEquals($x, $f->getRoot());
        echo '-----------------------' . PHP_EOL;
        $this->assertTrue($g->hasComponentDef(__NAMESPACE__ . '\X'));
        echo '-----------------------' . PHP_EOL;
        $this->assertTrue(!$g->hasComponentDef('qqq'));
        $this->assertTrue($g->hasComponentDef('ns_a.ns_b.ns_d.ns_g'));
        $this->assertTrue($g->hasComponentDef('ns_a.ns_c.ns_e.ns_g'));
        $this->assertTrue($g->hasComponentDef('ns_a.ns_c.ns_e.ns_g'));
        $this->assertTrue($g->hasComponentDef('ns_x.ns_y.ns_f.ns_g'));
        $this->assertTrue($g->hasComponentDef('ns_x.ns_y.ns_f.ns_g.Gg'));
        $this->assertTrue($b->hasComponentDef(__NAMESPACE__ . '\F'));
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_S2ContainerImplTest{}

class B_S2ContainerImplTest{}

class C_S2ContainerImplTest{}

class D_S2ContainerImplTest{
    public $a_S2ContainerImplTest = 's2binding';
}

class A{}

class B{}

class C{}

class D{}

/**
 * @S2Component('name' => 'e_s2container')
 */
class E{}

class F{}

class G{}

class X{}

class Y{}

