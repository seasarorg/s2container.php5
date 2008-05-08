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
 * AroundインターセプタのAbstractクラス
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.aop.interceptor
 * @author    klove
 */
namespace seasar::aop::interceptor;
abstract class AbstractAroundInterceptor implements seasar::aop::MethodInterceptor {
    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(seasar::aop::MethodInvocation $invocation) {
        $result = $this->before($invocation);
        if ($result === false) { 
            seasar::log::S2Logger::getInstance(__CLASS__)->info('before method returned false. will not proceed.', __METHOD__);
        } else {
            $result = $invocation->proceed();
        }
        return $this->after($invocation, $result);
    }

    /**
     * MethodInvocation::proceedメソッドの実行前に実行するbeforeメソッド
     * 戻り値としてboolean:falseを返した場合は、proceedは実行されません。
     *
     * @param MethodInvocation
     * @return boolean
     */
    abstract protected function before(seasar::aop::MethodInvocation $invocation);

    /**
     * MethodInvocation::proceedメソッドの実行後に実行するafterメソッド
     * 引数としてproceedメソッド結果が渡されます。
     *
     * @param MethodInvocation
     * @param mixed $result
     * @return mixed $result
     */
    abstract protected function after(seasar::aop::MethodInvocation $invocation, $result);
}
