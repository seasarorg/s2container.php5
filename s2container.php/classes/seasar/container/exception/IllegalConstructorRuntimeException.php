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
 * コンポーネントの構築に失敗した場合にスローされます。
 * <p>
 * コンポーネントの構築は、 コンポーネント定義でコンストラクタの引数として指定されたコンポーネントの取得に失敗した場合などに発生します。
 * </p>
 * 
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.exception
 * @author    klove
 */
namespace seasar::container::exception;
class IllegalConstructorRuntimeException extends seasar::exception::S2RuntimeException {

    /**
     * @var ReflectionClass
     */
    private $componentClass;

    /**
     * <b>S2Container_IllegalConstructorRuntimeException</b>を構築します。
     * 
     * @param ReflectionClass $componentClass 構築に失敗したコンポーネントのクラス
     * @param Exception       $cause コンポーネントの構築に失敗した原因を表すエラーまたは例外
     */
    public function __construct(ReflectionClass $componentClass) {
        $this->componentClass = $componentClass;
        parent::__construct(111, (array)$componentClass->getName());
    }

    /**
     * 構築に失敗したコンポーネントのクラスを返します。
     * 
     * @return ReflectionClass 構築に失敗したコンポーネントのクラス
     */
    public function getComponentClass() {
        return $this->componentClass;
    }
}
