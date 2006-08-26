<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
 * コンポーネントのコンストラクタおよびメソッドに与えられる引数定義のためのインターフェースです。
 * 
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_ArgDef extends S2Container_MetaDefAware
{
    /**
     * 引数定義の値を返します。
     * <P>
     * 引数定義の値とは、diconファイルに記述した<b><arg></b>要素の内容です。
     * インジェクションする際に、コンストラクタや初期化メソッド等の引数値になります。
     * </P>
     * 
     * @return mixed 引数定義の値
     */
    public function getValue();
    
    /**
     * 引数定義の値を設定します。
     * 
     * @param mixed $value 引数定義の値
     */
    public function setValue($value);

    /**
     * 引数を評価するコンテキストとなるS2コンテナを返します。
     * 
     * @return S2Container 引数を評価するコンテキストとなるS2コンテナ
     */
    public function getContainer();
    
    /**
     * 引数を評価するコンテキストとなるS2コンテナを設定します。
     * 
     * @param S2Container $container 引数を評価するコンテキストとなるS2コンテナ
     */
    public function setContainer(S2Container $container);
    
    /**
     * 引数定義の値となる式を返します。
     * 
     * @return string 引数定義の値となる式
     */
    public function getExpression();

    /**
     * 引数定義の値となる式を設定します。
     * 
     * @param string $expression 引数定義の値となる式
     */
    public function setExpression($expression);
    
    /**
     * 引数定義の値となるコンポーネント定義を設定します。
     * 
     * @param S2Container_ComponentDef $componentDef 
     *        引数定義の値となるコンポーネント定義
     */
    public function setChildComponentDef(S2Container_ComponentDef $componentDef);

}
?>
