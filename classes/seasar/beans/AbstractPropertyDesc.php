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
 * プロパティの定義を行う抽象クラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.beans
 * @author    klove
 */
namespace seasar::beans;
abstract class AbstractPropertyDesc implements PropertyDesc {

    /**
     * @var string
     */
    protected $typehint = null;

    /**
     * @var ReflectionClass
     */
    protected $typehintClass = null;

    /**
     * @var boolean
     */
    protected $isArrayAcceptable = false;

    /**
     * @var ReflectionClass
     */
    protected $beanClass = null;

    /**
     * @var string
     */
    protected $propertyName = null;

    /**
     * PropertyDescを構築します。
     *
     * @param ReflectionClass $beanClass
     * @param string $propName
     */
    public function __construct(ReflectionClass $beanClass, $propName) {
        $this->beanClass = $beanClass;
        $this->propertyName = $propName;
    }

    /**
     * ReflectionClassインスタンスを返します。
     * @return ReflectionClass
     */
    public function getBeanClass() {
        return $this->beanClass;
    }

    /**
     * プロパティ名を返します。
     * @return string
     */
    public function getPropertyName() {
        return $this->propertyName;
    }

    /**
     * タイプヒントを返します。
     * @return string
     */
    public function getTypehint() {
        return $this->typehint;
    }

    /**
     * タイプヒントを返します。
     * @return string
     */
    public function setTypehint($value) {
        $this->typehint = $value;
    }

    /**
     * タイプヒントを返します。
     * @return ReflectionClass
     */
    public function getTypehintClass() {
        return $this->typehintClass;
    }

    /**
     * タイプヒントを返します。
     * @return ReflectionClass
     */
    public function setTypehintClass(ReflectionClass $clazz) {
        $this->typehintClass = $clazz;
    }

    /**
     * 配列が指定されているかどうかを返します。
     * @return boolean
     */
    public function isArrayAcceptable() {
        return $this->isArrayAcceptable;
    }

    /**
     * 配列が指定されているかどうかを設定します。
     * @param boolean $value
     */
    public function setArrayAcceptable($value = true) {
        $this->isArrayAcceptable = $value;
    }
}
