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
 * コンポーネントに対して<d>destroy</d>メソッド・インジェクションを定義するためのインターフェースです。
 * <p>
 * <d>destroy</d>メソッド・インジェクションとは、 S2コンテナによって管理されているコンポーネントが破棄される際に、
 * 1個以上の任意のメソッド(終了処理メソッド)を実行するという機能です。
 * </p>
 * <p>
 * コンポーネントのコンポーネントインスタンス定義が<d>singleton</d>の場合には、
 * S2コンテナが終了する際に<d>destroy</d>メソッド・インジェクションが実行されます。
 * </p>
 *
 * @see S2Container_ComponentDeployer::destroy()
 * @see S2Container_ComponentDef::destroy()
 * @see S2Container_S2Container::destroy()
 *  
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_DestroyMethodDef
    extends S2Container_MethodDef
{
}
?>
