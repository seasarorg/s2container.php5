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
 * MethodInvocationをあらわすインターフェースです。
 * @see http://aopalliance.sourceforge.net/doc/org/aopalliance/intercept/MethodInvocation.html
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar::aop;
interface MethodInvocation {

    /**
     * @see http://aopalliance.sourceforge.net/doc/org/aopalliance/intercept/MethodInvocation.html#getMethod()
     * @return ReflectionMethod
     */
    public function getMethod();

    /**
     * アスペクト対象のクラスを返します。
     * @return ReflectionClass
     */
    public function getTargetClass();

    /**
     * アスペクト対象のクラスのEnhancedクラスを返します。
     * @return ReflectionClass
     */
    public function getConcreteClass();

    /**
     * Enhancedクラスに実装されているメソッドのReflectionMethodを返します。
     * @return ReflectionMethod
     */
    public function getConcreteMethod();

    /**
     * @see http://aopalliance.sourceforge.net/doc/org/aopalliance/intercept/Invocation.html#getArguments()
     * @return array
     */
    public function getArguments();

    /**
     * S2Aop.PHP用の拡張パラメータを返します。
     * @param string $name
     * @return mixed
     */
    public function getParameter($name);

    /**
     * @see http://aopalliance.sourceforge.net/doc/org/aopalliance/intercept/Joinpoint.html#getThis()
     * @return object
     */
    public function getThis();

    /**
     * @see http://aopalliance.sourceforge.net/doc/org/aopalliance/intercept/Joinpoint.html#proceed()
     * @return mixed
     */
    public function proceed();
}
