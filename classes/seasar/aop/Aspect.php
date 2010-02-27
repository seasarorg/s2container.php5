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
 * Aspectをあらわすクラスです。 Aspectとは、 Advice(MethodInterceptor)とPointcutを結びつけたものです。
 * 
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar\aop;
class Aspect {

    /**
     * @var \seasar\aop\MethodInterceptor
     */
    private $methodInterceptor;

    /**
     * @var \seasar\aop\Pointcut
     */
    private $pointcut;

    /**
     * Aspectを構築します。
     * @param \seasar\aop\MethodInterceptor $methodInterceptor
     * @param \seasar\aop\Pointcut $pointcut
     */
    public function __construct(MethodInterceptor $methodInterceptor, Pointcut $pointcut) {
        $this->methodInterceptor = $methodInterceptor;
        $this->pointcut = $pointcut;
    }

    /**
     * MethodInterceptorを返します。
     * @return \seasar\aop\MethodInterceptor
     */
    public function getMethodInterceptor() {
        return $this->methodInterceptor;
    }

    /**
     * Pointcutを返します。
     * @return \seasar\aop\Pointcut
     */
    public function getPointcut() {
        return $this->pointcut;
    }

    /**
     * Pointcutを設定します。
     * @param \seasar\aop\Pointcut $pointcut
     */
    public function setPointcut(Pointcut $pointcut) {
        $this->pointcut = $pointcut;
    }
}
