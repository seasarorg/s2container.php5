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
 * S2コンテナ内に1つのキーで複数登録されたコンポーネントの定義を表すインタフェースです。
 * <p>
 * S2コンテナにコンポーネントが登録される際に、 そのキー(コンポーネントのクラス、
 * インターフェース、あるいは名前)に対応するコンポーネントがすでに登録されていると、 コンポーネント定義が<d>S2Container_TooManyRegistrationComponentDef</d>になります。
 * </p>
 * <p>
 * <d>S2Container_TooManyRegistrationComponentDef</d>で定義されているコンポーネントを取得しようとすると、
 * {@link S2Container_TooManyRegistrationRuntimeException}がスローされます。
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
interface S2Container_TooManyRegistrationComponentDef
{
    /**
     * 同じキーで登録されたコンポーネント定義を追加します。
     * 
     * @param S2Container_ComponentDef $componentDef
     *            同じキーで登録されたコンポーネント定義
     */
    public function addComponentDef($componentDef);

    /**
     * 複数登録されたコンポーネントの定義上のクラスの配列を返します。
     * 
     * @return array 複数登録されたコンポーネントの定義上のクラスの配列
     */
    public function getComponentClasses();

    /**
     * 複数登録されたコンポーネント定義の配列を返します。
     * 
     * @return array 複数登録されたコンポーネント定義の配列
     */
    public function getComponentDefs();
}
?>
