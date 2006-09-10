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
 * コンポーネントのプロパティまたはフィールドにインジェクションする方法を定義するインタフェースです。
 * 
 * <p>
 * プロパティ定義は、diconファイルにおける<d><property></d>要素で指定されます。
 * <d><property></d>要素にはname属性があります。
 * </p>
 * <p>
 * <ul>
 * <li>name属性はコンポーネントのプロパティ名またはフィールド名を指定します。</li>
 * </ul>
 * </p>
 * <p>
 * <d><property></d>要素の内容に指定された式またはコンポーネントが、
 * <d><property></d>要素のname属性で指定されたプロパティまたはフィールドに設定されます。
 * </p>
 * <p>
 * プロパティ定義が存在する場合のプロパティインジェクション　diconファイルに記述されているプロパティ定義に従って行われます。
 * プロパティ定義が存在しない場合、バインディングモード設定に従って自動バインディングが行われる事があります。
 * </p>
 * 
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_PropertyDef extends S2Container_ArgDef
{
    /**
     * インジェクション対象となるプロパティ名またはフィールド名を返します。
     * 
     * @return string 設定対象となるプロパティ名
     */
    public function getPropertyName();
}
?>
