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
 * コンポーネントの構築に失敗した場合にスローされます。
 * <p>
 * コンポーネントの構築は、 コンポーネント定義でコンストラクタの引数として指定されたコンポーネントの取得に失敗した場合などに発生します。
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
class S2Container_IllegalConstructorRuntimeException
    extends S2Container_S2RuntimeException
{
    private $componentClass;

    /**
     * <b>S2Container_IllegalConstructorRuntimeException</b>を構築します。
     * 
     * @param ReflectionClass $componentClass 構築に失敗したコンポーネントのクラス
     * @param Exception       $cause コンポーネントの構築に失敗した原因を表すエラーまたは例外
     */
    public function __construct(ReflectionClass $componentClass,Exception $cause = null)
    {
        parent::__construct("ESSR0058",
                            array($componentClass->getName(),
                                  $cause),
                            $cause);
        $this->componentClass = $componentClass;
    }

    /**
     * 構築に失敗したコンポーネントのクラスを返します。
     * 
     * @return ReflectionClass 構築に失敗したコンポーネントのクラス
     */
    public function getComponentClass()
    {
        return $this->componentClass;
    }
}
?>
