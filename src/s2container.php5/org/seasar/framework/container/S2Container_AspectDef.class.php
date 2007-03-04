<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * コンポーネントに適用するアスペクトを定義するインターフェースです。
 * 
 * <p>
 * S2AOP(Seasar2 Aspect Oriented Programming)には、基本要素として以下のものがあります。
 * <ul>
 * <li>アスペクト({@link S2Container_Aspect}) <br>
 *     ポイントカットとインターセプタの関連を表します。 </li>
 * <li>ポイントカット({@link S2Container_Pointcut}) <br>
 *     インターセプタが実行されるメソッドの集合を表します。</li>
 * <li>インターセプタ({@link S2Container_Interceptor}) <br>
 *     ポイントカットで実行される共通的な処理を表します。</li>
 * </ul>
 * 
 * インターセプタは、 より一般的にアドバイス({@link S2Container_Advice})と呼ばれます。
 * </p>
 * <p>
 * S2AOPにおけるインターセプタは、
 * {@link S2Container_MethodInterceptor}インターフェースを実装したクラスのコンポーネントとして定義します。
 * {@link S2Container_InterceptorChain}を使用することで、
 * 複数のインターセプタを1つのインターセプタ・コンポーネントとして定義することが可能です。
 * </p>
 * <p>
 * 1つのコンポーネントに複数のアスペクトを定義することが可能です。 定義した順にアスペクトのインターセプタが実行されます。
 * </p>
 * 
 * @copyright  2005-2007 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_AspectDef extends S2Container_ArgDef
{

    /**
     * ポイントカットを返します。
     * 
     * @return S2Container_Pointcut ポイントカット
     */
    public function getPointcut();

    /**
     * ポイントカットを設定します。
     * 
     * @param S2Container_Pointcut $pointcut
     */
    public function setPointcut(S2Container_Pointcut $pointcut);

    /**
     * アスペクトを返します。
     * 
     * @return S2Container_AspectImpl アスペクト
     */
    public function getAspect();
}
?>
