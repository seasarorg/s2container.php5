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
 * @package   seasar.aop.impl
 * @author    klove
 */
namespace seasar::aop::impl;
class NestedMethodInvocationTest extends ::PHPUnit_Framework_TestCase {

    public function testChainInvocation() {
        $targetRef = new ReflectionClass('seasar::aop::impl::A_NestedMethodInvocation');
        $interceptor = new seasar::aop::interceptor::InterceptorChain;
        $pointcut = new seasar::aop::Pointcut('/.+/');
        $aspects = array(new seasar::aop::Aspect($interceptor, $pointcut));
        $targetOjb = seasar::aop::S2AopFactory::create($targetRef, $aspects, array());
        $targetOjb->service();

        $interceptor->add(new seasar::aop::interceptor::TraceInterceptor);
        $interceptor->add(new seasar::aop::interceptor::TraceInterceptor);
        $targetOjb->service();
    }

    public function testPrameter() {
        $targetRef = new ReflectionClass('seasar::aop::impl::A_NestedMethodInvocation');
        $interceptor = new B_NestedMethodInvocation;
        $pointcut = new seasar::aop::Pointcut('/.+/');
        $aspects = array(new seasar::aop::Aspect($interceptor, $pointcut));
        $targetOjb = seasar::aop::S2AopFactory::create($targetRef, $aspects, array(), array('name' => 'hoge'));
        $targetOjb->service();

        $invocation = $interceptor->getNestInvocation();
        $this->assertTrue($invocation instanceof seasar::aop::MethodInvocation);
        $this->assertTrue($invocation->getThis() instanceof seasar::aop::impl::A_NestedMethodInvocation);
        $this->assertTrue($invocation->getArguments() === array());
        $this->assertTrue($invocation->getMethod()->getName() === 'service');
        $this->assertTrue($invocation->getTargetClass()->getName() === 'seasar::aop::impl::A_NestedMethodInvocation');
        $this->assertTrue($invocation->getConcreteClass()->getName() === 'seasar::aop::impl::A_NestedMethodInvocation' .  seasar::aop::EnhancedClassGenerator::CLASS_NAME_POSTFIX);
        $this->assertTrue($invocation->getParameter('name') === 'hoge');
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_NestedMethodInvocation {
    public function service() {
        print __METHOD__ . ' called.' . PHP_EOL;
    }
}

class B_NestedMethodInvocation implements seasar::aop::MethodInterceptor {
    private $nestInvocation = null;
    public function invoke(seasar::aop::MethodInvocation $invocation) {
        $this->nestInvocation = new seasar::aop::impl::NestedMethodInvocation($invocation, array());
        
        return $this->nestInvocation->proceed();
    }
    
    public function getNestInvocation() {
        return $this->nestInvocation;
    }
}
