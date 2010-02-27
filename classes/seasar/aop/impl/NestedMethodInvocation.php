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
 * 複数のinterceptorを管理するinvocationクラス。InterceptorChainクラスで使用される。
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop.impl
 * @author    klove
 */
namespace seasar\aop\impl;
class NestedMethodInvocation implements \seasar\aop\MethodInvocation {

    /**
     * @var \seasar\aop\MethodInvocation $parent 親となるinvocationインスタンス
     */
    private $parent = null;

    /**
     * @var array 管理するインターセプター群
     */
    private $interceptors = null;

    /**
     * @var integer 現在のインターセプターインデックス
     */
    private $interceptorsIndex = 0;

    /**
     * @param \seasar\aop\MethodInvocation 親となるinvocationインスタンス
     * @param array 管理するインターセプター群
     */
    public function __construct(\seasar\aop\MethodInvocation $parent, array $interceptors) {
        $this->parent = $parent;
        $this->interceptors = $interceptors;
    }

    /**
     * @see \seasar\aop\MethodInvocation::proceed()
     */
    public function proceed() {
        if ($this->interceptorsIndex < count($this->interceptors)) {
            return $this->interceptors[$this->interceptorsIndex++]->invoke($this);
        }
        return $this->parent->proceed();
    }

    /**
     * @see \seasar\aop\MethodInvocation::getThis()
     */
    public function getThis() {
        return $this->parent->getThis();
    }

    /**
     * @see \seasar\aop\MethodInvocation::getArguments()
     */
    public function getArguments() {
        return $this->parent->getArguments();
    }

    /**
     * @see \seasar\aop\MethodInvocation::getMethod()
     */
    public function getMethod() {
        return $this->parent->getMethod();
    }

    /**
     * アスペクト対象のクラスを返します。
     * @return \ReflectionClass
     */
    public function getTargetClass() {
        return $this->parent->getTargetClass();
    }

    /**
     * Enhancedクラスに実装されているメソッドのReflectionMethodを返します。
     * @return \ReflectionMethod
     */
    public function getConcreteClass() {
        return $this->parent->getConcreteClass();
    }

    /**
     * S2Aop.PHP用の拡張パラメータを返します。
     * @param string $name
     * @return mixed
     */
    public function getParameter($name) {
        return $this->parent->getParameter($name);
    }
}
