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
 * このインターフェースは、 アスペクト定義を登録および取得することができるオブジェクトを表します。
 * <p>
 * アスペクト定義は複数登録することが出来ます。 アスペクト定義の取得はインデックス番号を指定して行います。
 * </p>
 * 
 * @see S2Container_AspectDef
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_AspectDefAware
{
    /**
     * アスペクト定義を登録(追加)します。
     * 
     * @param S2Container_AspectDef $aspectDef アスペクト定義
     */    
    public function addAspectDef(S2Container_AspectDef $aspectDef);
    
    /**
     * 登録されている{@link S2Container_AspectDef アスペクト定義}の数を返します。
     * <p>
     * 登録されている{@link S2Container_MethodInterceptor インターセプタ}の数ではなく、
     * アスペクト定義の数を返します。 アスペクト定義のコンポーネント(インターセプタ)のクラスが
     * {@link S2Container_InterceptorChain InterceptorChain}で、
     * その中に複数のインターセプタが含まれる場合も、 1つのアスペクト定義としてカウントします。
     * </p>
     * 
     * @return integer 登録されているアスペクト定義の数
     */
    public function getAspectDefSize();
    
    /**
     * 指定されたインデックス番号<b>index</b>のアスペクト定義を返します。
     * <p>
     * インデックス番号は、 登録した順番に 0,1,2,… となります。
     * </p>
     * 
     * @param integer $index
     *            アスペクト定義を指定するインデックス番号
     * @return S2Container_AspectDef アスペクト定義
     */
    public function getAspectDef($index);
}
?>
