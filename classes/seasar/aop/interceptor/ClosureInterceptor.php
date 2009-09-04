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
 * クロージャーをラップするMethodInterceptorです。
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   seasar.aop.interceptor
 * @author    klove
 */
namespace seasar\aop\interceptor;
class ClosureInterceptor implements \seasar\aop\MethodInterceptor {

    /**
     * @var \Closure
     */
    private $closure = null;

    /**
     * ClosureInterceptor を構築します。
     *
     * @param \Closure $closure
     * @return void
     */
    public function __construct(\Closure $closure) {
        $this->closure = $closure;
    }

    /**
     * Closureを設定します。
     *
     * @param \Closure $closure
     */
    public function setClosure(\Closure $closure) {
        $this->closure = $closure;
    }

    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(\seasar\aop\MethodInvocation $invocation) {
        $closure = $this->closure;
        return $closure($invocation);
    }
}
