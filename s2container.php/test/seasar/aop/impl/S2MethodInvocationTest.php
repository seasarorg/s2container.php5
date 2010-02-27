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
 * @package   seasar.aop.impl
 * @author    klove
 */
namespace seasar\aop\impl;
class S2MethodInvocationTest extends \PHPUnit_Framework_TestCase {

    public function testPrameter() {
        $targetRef = new \ReflectionClass('\seasar\aop\impl\A_S2MethodInvocation');
        $interceptor = new B_S2MethodInvocation;
        $pointcut = new \seasar\aop\Pointcut('/.+/');
        $aspects = array(new \seasar\aop\Aspect($interceptor, $pointcut));
        $targetOjb = \seasar\aop\S2AopFactory::create($targetRef, $aspects, array(), array('name' => 'hoge'));
        $targetOjb->service();

        $invocation = $interceptor->getInvocation();
        $this->assertTrue($invocation instanceof \seasar\aop\MethodInvocation);
        $this->assertTrue($invocation->getThis() instanceof \seasar\aop\impl\A_S2MethodInvocation);
        $this->assertTrue($invocation->getArguments() === array());
        $this->assertTrue($invocation->getMethod()->getName() === 'service');
        $this->assertTrue($invocation->getTargetClass()->getName() === 'seasar\aop\impl\A_S2MethodInvocation');
        $this->assertTrue($invocation->getConcreteClass()->getName() === 'seasar\aop\impl\A_S2MethodInvocation' .  \seasar\aop\EnhancedClassGenerator::CLASS_NAME_POSTFIX);
        $this->assertTrue($invocation->getParameter('name') === 'hoge');
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_S2MethodInvocation {
    public function service() {
        print __METHOD__ . ' called.' . PHP_EOL;
    }
}

class B_S2MethodInvocation implements \seasar\aop\MethodInterceptor {
    private $invocation = null;
    public function invoke(\seasar\aop\MethodInvocation $invocation) {
        $this->invocation = $invocation;
        return $this->invocation->proceed();
    }
    
    public function getInvocation() {
        return $this->invocation;
    }
}
