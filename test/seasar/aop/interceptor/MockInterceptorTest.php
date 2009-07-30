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
 * @package   seasar.aop.interceptor
 * @author    klove
 */
namespace seasar\aop\interceptor;
class MockInterceptorTest extends \PHPUnit_Framework_TestCase {

    public function testMock() {
        $container = new \seasar\container\impl\S2ContainerImpl;
        $c = $container->getComponent(__NAMESPACE__ . '\C_MockInterceptorTest');
        $this->assertEquals(2007, $c->getName());
        $this->assertEquals(1234, $c->getValue());
        try {
            $c->getYear();
            $this->fail();
        } catch(\Exception $e) {
            $this->assertEquals('mock exception', $e->getMessage());
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

/**
 * @S2Aspect('interceptor' => 'new \seasar\aop\interceptor\MockInterceptor', 'pointcut' => '/.+/')
 */
abstract class C_MockInterceptorTest{
    /**
     * @S2Mock('return' => 2007);
     */
    abstract public function getName();

    /**
     * @S2Mock('throw' => 'new \Exception("mock exception")');
     */
    abstract public function getYear();

    /**
     * @S2Mock('val' => 4321);
     */
    public function getValue() {
        return 1234;
    }
}

