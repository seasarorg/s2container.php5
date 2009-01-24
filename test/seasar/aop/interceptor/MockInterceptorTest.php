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
 * @package   seasar.aop.interceptor
 * @author    klove
 */
namespace seasar\aop\interceptor;
class MockInterceptorTest extends \PHPUnit_Framework_TestCase {

    public function test01Proceed() {
        $container = \seasar\container\factory\S2ContainerFactory::create(dirname(__FILE__) . '/MockInterceptorTest_dicon/test01Proceed.dicon');
        $a = $container->getComponent('a');
        $a->hoge();
    }

    public function test02ReturnValue() {
        $container = \seasar\container\factory\S2ContainerFactory::create(dirname(__FILE__) . '/MockInterceptorTest_dicon/test02ReturnValue.dicon');
        $b = $container->getComponent('b');
        $this->assertEquals($b->getName(), 'abc');
    }

    public function test03Exception() {
        $container = \seasar\container\factory\S2ContainerFactory::create(dirname(__FILE__) . '/MockInterceptorTest_dicon/test03Exception.dicon');
        $b = $container->getComponent('b');
        try {
            $b->getName();
            $this->fail();
        } catch(\Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function test04Annotation() {
        $container = \seasar\container\factory\S2ContainerFactory::create(dirname(__FILE__) . '/MockInterceptorTest_dicon/test04Annotation.dicon');
        $c = $container->getComponent('c');
        $this->assertEquals($c->getName(), 2007);

        try {
            $c->getYear();
            $this->fail();
        } catch(\Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }

        $this->assertEquals($c->getValue(), 1234);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_MockInterceptorTest{
    public function hoge() {
        print __METHOD__ . ' called.' . PHP_EOL;
    }
}

class B_MockInterceptorTest{
    public function getName() {
        return null;
    }
}

abstract class C_MockInterceptorTest{
    /**
     * @S2Mock('return' => 2007);
     */
    abstract public function getName();

    /**
     * @S2Mock('exception' => new \Exception('annotation exception'));
     */
    abstract public function getYear();

    /**
     * @S2Mock('val' => 4321);
     */
    public function getValue() {
        return 1234;
    }
}

