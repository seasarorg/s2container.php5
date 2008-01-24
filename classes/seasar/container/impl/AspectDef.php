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
 * コンポーネントに適用するアスペクトを定義するクラスです。
 *
 * 1つのコンポーネントに複数のアスペクトを定義することが可能です。
 * 定義した順にアスペクトのインターセプタが実行されます。
 * S2AOPにおけるインターセプタは、 MethodInterceptorインターフェースを実装したクラスのコンポーネントとして定義します。
 * インターセプターのセットを、複数のコンポーネントに適用する場合には、 複数のインターセプタを1つのインターセプタ・コンポーネントとして定義できる、
 * InterceptorChainを使用すると設定を簡略化できます。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar::container::impl;
class AspectDef extends ArgDef {

    /**
     * @var seasar::aop::Pointcut
     */
    private $pointcut = null;

    /**
     * AspectDef を構築します。
     *
     * @param seasar::aop::Pointcut $pointcut
     * @param seasar::aop::MethodInterceptor $interceptor
     */
    public function __construct(seasar::aop::Pointcut $pointcut = null, seasar::aop::MethodInterceptor $interceptor = null) {
        parent::__construct($interceptor);
        $this->pointcut = $pointcut;
    }

    /**
     * ポイントカットを返します。
     *
     * @return seasar::aop::Pointcut
     */
    public function getPointcut() {
        return $this->pointcut;
    }

    /**
     * ポイントカットを返します。
     *
     * @param seasar::aop::Pointcut $pointcut
     */
    public function setPointcut(seasar::aop::Pointcut $pointcut) {
        $this->pointcut = $pointcut;
    }

    /**
     * アスペクトを返します。
     *
     * @return seasar::aop::Aspect
     */
    public function getAspect() {
        return new seasar::aop::Aspect($this->getValue(), $this->pointcut);
    }
}
