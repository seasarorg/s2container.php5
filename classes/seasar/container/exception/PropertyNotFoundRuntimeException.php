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
 * Manualインジェクションに失敗した場合にスローされる例外クラスです。
 * <p>
 * Manualインジェクションで指定されたプロパティが対象クラスに存在していない場合にスローされます。
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
class PropertyNotFoundRuntimeException extends seasar::exception::S2RuntimeException {

    /**
     * @var ReflectionClass
     */
    private $componentClass;

    /**
     * PropertyNotFoundRuntimeExceptionを構築します。
     * @param ReflectionClass $componentClass Manualインジェクションに失敗したコンポーネントのクラス
     * @param sring $propertyName Manualインジェクションに失敗したプロパティ名
     */
    public function __construct(ReflectionClass $componentClass, $propertyName) {
        $this->componentClass = $componentClass;
        $this->propertyName   = $propertyName;
        parent::__construct(113, array($componentClass->getName(), $propertyName));
    }

    /**
     * Manualインジェクションに失敗したコンポーネントのクラスを返します。
     * 
     * @return ReflectionClass Manualインジェクションに失敗したコンポーネントのクラス
     */
    public function getComponentClass() {
        return $this->componentClass;
    }

    /**
     * Manualインジェクションに失敗したプロパティ名を返します。
     * 
     * @return string Manualインジェクションに失敗したプロパティ名
     */
    public function getPropertyName() {
        return $this->getPropertyName;
    }
}
