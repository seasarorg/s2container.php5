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
 * メソッド・インジェクションを定義するためのインタフェースです。
 * <p>
 * メソッド・インジェクションとは、任意のメソッドや式の呼び出しによりコンポーネントをインジェクションすることです。
 * </p>
 * <p>
 * 例として、<d>addFoo(Foo)</d> メソッドを通じて <d>Foo</d>をインジェクションする場合に利用することができます。
 * 引数のないメソッドや任意の式を呼び出すこともできます。
 * </p>
 * <p>
 * コンポーネントが初期化されるときに実行されるinitMethodインジェクションと、
 * コンテナの終了時に実行されるdesoryMethodインジェクションがあります。 destroyMethodインジェクションが適用されるのは、
 * コンポーネントのinstance要素が<d>singleton</d>の場合だけです。
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
interface S2Container_MethodDef extends S2Container_ArgDefAware
{
    /**
     * メソッド名を返します。
     * 
     * @return string メソッド名
     */    
    public function getMethodName();
    
    /**
     * メソッド引数を返します。
     * 
     * @return array メソッド引数
     */
    public function getArgs();

    /**
     * 引数および式を評価するコンテキストとなるS2コンテナを返します。
     * 
     * @return S2Container 引数および式を評価するコンテキストとなるS2コンテナ
     */
    public function getContainer();
    
    /**
     * 引数および式を評価するコンテキストとなるS2コンテナを設定します。
     * 
     * @param S2Container $container
     *            引数および式を評価するコンテキストとなるS2コンテナ
     */
    public function setContainer(S2Container $container);
    
    /**
     * 実行される式を返します。
     * 
     * @return string 実行される式
     */
    public function getExpression();
    
    /**
     * 実行される式を設定します。
     * 
     * @param string expression 実行される式
     */
    public function setExpression($expression);
}
?>
