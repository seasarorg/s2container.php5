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
 * S2Aop.PHP用に拡張を行ったMethodInvocationを実装クラス
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop.impl
 * @author    klove
 */
namespace seasar\aop\impl;
class S2MethodInvocationImpl implements \seasar\aop\MethodInvocation {
    private $interceptorIndex = 0;
    private $interceptors     = null;
    private $target           = null;
    private $targetClass      = null;
    private $method           = null;
    private $arguments        = null;
    private $parameters       = null;
    private $concreteClass    = null;

    /**
     * @param object $target MethodInvocationを保持するインスタンス
     * @param \ReflectionClass $targetClass Aspect対象クラスのReflectionClass
     * @param \ReflectionMethod $method Aspect対象メソッドのReflectionMethod
     * @param \ReflectionClass $concreteClass Aspect対象クラスをEnhanceしたクラスのReflectionClass
     * @param array $arguments Aspect対象メソッドの呼び出し引数
     * @param array $interceptors Aspectするインターセプタ群
     * @param array $parameters S2Aop.PHP用の拡張パラメータ
     */
    public function __construct($target,
                         \ReflectionClass $targetClass,
                         \ReflectionMethod $method,
                         \ReflectionClass $concreteClass,
                         array $arguments,
                         array $interceptors,
                         array $parameters = null) {
        $this->target         = $target;
        $this->targetClass    = $targetClass;
        $this->method         = $method;
        $this->concreteClass  = $concreteClass;
        $this->arguments      = $arguments;
        $this->interceptors   = $interceptors;
        $this->parameters     = $parameters;
    }

    /**
     * アスペクト対象のクラスを返します。
     * @return \ReflectionClass
     */
    public function getTargetClass() {
        return $this->targetClass;
    }

    /**
     * Enhancedクラスに実装されているメソッドのReflectionMethodを返します。
     * @return \ReflectionMethod
     */
    public function getConcreteClass() {
        return $this->concreteClass;
    }

    /**
     * S2Aop.PHP用の拡張パラメータを返します。
     * @param string $name
     * @return mixed
     */
    public function getParameter($name) {
        if (array_key_exists($name, $this->parameters)) {
             return $this->parameters[$name];
        }
        return null;
    }

    /**
     * @see \seasar\aop\MethodInvocation::getMethod()
     */
    function getMethod() {
        return $this->method;
    }

    /**
     * @see \seasar\aop\MethodInvocation::getArguments()
     */
    function getArguments() {
         return $this->arguments;
    }

    /**
     * @see \seasar\aop\MethodInvocation::getThis()
     */
    function getThis() {
        return $this->target;
    }

    /**
     * @see \seasar\aop\MethodInvocation::proceed()
     */
    function proceed() {
        if ($this->interceptorIndex < count($this->interceptors)) {
            return $this->interceptors[$this->interceptorIndex++]->invoke($this);
        } else {
            return \seasar\util\MethodUtil::invoke($this->concreteClass->getMethod('__invokeParentMethod_EnhancedByS2AOP'), $this->target, array_merge($this->arguments, array($this->method->getName())));
        }
    }
}
