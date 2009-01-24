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
class TraceInterceptorTest extends \PHPUnit_Framework_TestCase {

    public function test01() {
        $container = \seasar\container\factory\S2ContainerFactory::create(dirname(__FILE__) . '/TraceInterceptorTest_dicon/test01.dicon');
        $a = $container->getComponent('a');
        $a->hoge();
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_TraceInterceptorTest{
    public function hoge() {
        print __METHOD__ . ' called.' . PHP_EOL;
    }
}

class B_TraceInterceptorTest{}

class C_TraceInterceptorTest{}

