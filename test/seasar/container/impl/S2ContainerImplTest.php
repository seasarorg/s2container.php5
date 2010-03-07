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
class S2ContainerImplTest extends \PHPUnit_Framework_TestCase {

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

    public function testComponentDefNotFound() {
        $container = new S2ContainerImpl();
        try {
            $container->getComponentDef('xxx');
            $this->fail();
        } catch(\seasar\container\exception\ComponentNotFoundRuntimeException $e) {
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

    public function testClassComponentDefBuilder() {
        $container = new S2ContainerImpl();
        $this->assertFalse($container->hasComponentDef('e_s2container'));
        $this->assertTrue($container->hasComponentDef(__NAMESPACE__ . '\E'));
        $this->assertTrue($container->hasComponentDef('e_s2container'));
    }

    public function setUp(){
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

