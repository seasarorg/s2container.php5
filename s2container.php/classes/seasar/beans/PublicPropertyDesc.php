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
 * アクセス修飾子がpublicであるプロパティを定義するクラスです。
 * プロパティのタイプヒントは次のように指定します。
 *     public $service = 'S2Binding DefaultService';
 *
 * タイプヒントにクラスの配列を指定する場合は、次のようになります。
 *     public $service = 'S2Binding DefaultService[]';
 *
 * タイプヒントにコンポーネントのキーを指定することができます。
 *     public $service = 'S2Binding service';
 *
 * タイプヒントにコンポーネントのキーを配列として指定することができます。
 *     public $service = 'S2Binding service[]';
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
class PublicPropertyDesc extends AbstractPropertyDesc {

    /**
     * @var ReflectionProperty
     */
    private $property = null;

    /**
     * PublicPropertyDescを構築します。
     *
     * @see seasar::beans::AbstractPropertyDesc::_construct()
     */
    public function __construct(ReflectionClass $beanClass, $propName) {
        parent::__construct($beanClass, $propName);
        if (!$beanClass->hasProperty($propName)) {
            throw new seasar::exception::PropertyNotFoundRuntimeException($propName);
        }
        $this->property = $beanClass->getProperty($propName);
        if (!$this->property->isPublic()) {
            throw new seasar::exception::IllegalPropertyRuntimeException($propName);
        }
    }

    /**
     * ReflectionPropertyインスタンスを返します。
     * @return ReflectionProperty
     */
    public function getProperty() {
        return $this->property;
    }

    /**
     * ReflectionPropertyインスタンスを設定します。
     * @param ReflectionProperty $property
     */
    public function setProperty(ReflectionProperty $property) {
        $this->property = $property;
    }

    /**
     * @see seasar::beans::AbstractPropertyDesc::setValue()
     */
    public function setValue($instance, $value) {
        $this->property->setValue($instance, $value);
    }

    /**
     * @see seasar::beans::AbstractPropertyDesc::getValue()
     */
    public function getValue($instance) {
        return $this->property->getValue($instance);
    }

    /**
     * @see seasar::beans::AbstractPropertyDesc::getReflection()
     */
    public function getReflection() {
        return $this->property;
    }
}
