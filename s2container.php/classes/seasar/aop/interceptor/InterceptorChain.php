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
 * 複数のMethodInterceptorをチェイン上につなぐMethodInterceptor
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop.interceptor
 * @author    klove
 */
namespace seasar\aop\interceptor;
class InterceptorChain implements \seasar\aop\MethodInterceptor {

    /**
     * @var array 複数のMethodInterceptorを保存する配列
     */
    private $interceptors = array();

    /**
     * @param MethodInterceptor $interceptor MethodInterceptorを追加します。
     */
    public function add(\seasar\aop\MethodInterceptor $interceptor) {
        $this->interceptors[] = $interceptor;
    }

    /**
     * @param array $interceptors 複数のMethodInterceptorを登録します。
     */
    public function setInterceptors(array $interceptors) {
        $this->interceptors = $interceptors;
    }

    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(\seasar\aop\MethodInvocation $invocation) {
        $nestInvocation = new \seasar\aop\impl\NestedMethodInvocation($invocation, $this->interceptors);
        return $nestInvocation->proceed();
    }
}
