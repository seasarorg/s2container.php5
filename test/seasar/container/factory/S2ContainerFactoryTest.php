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
class S2ContainerFactoryTest extends \PHPUnit_Framework_TestCase {
    public static $DICON_DIR = null;

    public function test01Build() {
        $container = S2ContainerFactory::create(dirname(__FILE__) . '/S2ContainerFactoryTest_dicon/test01Build.dicon');
        $this->assertTrue($container instanceof \seasar\container\S2Container);
    }

    public function test02Include() {
        self::$DICON_DIR = dirname(__FILE__) . '/S2ContainerFactoryTest_dicon';
        $container = S2ContainerFactory::create(dirname(__FILE__) . '/S2ContainerFactoryTest_dicon/test02Include.dicon');
        $this->assertTrue($container instanceof \seasar\container\S2Container);
        $a = $container->getComponentDef('a');
        $parentA = $container->getComponentDef('parent.a');
        $childA = $container->getComponentDef('child.a');
        $this->assertTrue($parentA === $a);
        $this->assertFalse($parentA === $childA);
        $this->assertTrue($parentA->getComponentClass()->getName() === 'seasar\container\factory\A_S2ContainerFactoryTest');
        $this->assertTrue($childA->getComponentClass()->getName() === 'seasar\container\factory\A_S2ContainerFactoryTest');
    }

    public function test03CycleInclude() {
        self::$DICON_DIR = dirname(__FILE__) . '/S2ContainerFactoryTest_dicon';
        try {
            $container = S2ContainerFactory::create(dirname(__FILE__) . '/S2ContainerFactoryTest_dicon/test03CycleInclude.dicon');
            $this->fail();
        } catch(\seasar\container\exception\CircularIncludeRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function testIlligalBuilder() {
        try {
            $container = S2ContainerFactory::create(__FILE__);
            $this->fail();
        } catch(\seasar\container\exception\IllegalContainerBuilderRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_S2ContainerFactoryTest{}

class B_S2ContainerFactoryTest{}

class C_S2ContainerFactoryTest{}

