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
 * セッター・ゲッターメソッド用のプロパティを定義するクラスです。
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
class AccessorMethodPropertyDesc extends AbstractPropertyDesc {

    /**
     * @var ReflectionMethod
     */
    private $setterMethod = null;

    /**
     * @var ReflectionMethod
     */
    private $getterMethod = null;

    /**
     * AccessorMethodPropertyDescを構築します。
     *
     * @see seasar::beans::AbstractPropertyDesc::_construct()
     */
    public function __construct(ReflectionClass $beanClass, $propName) {
        parent::__construct($beanClass, $propName);
        $methodName = self::getSetterMethodName($propName);
        if ($beanClass->hasMethod($methodName)) {
            $this->setterMethod = $beanClass->getMethod($methodName);
        }
        $methodName = self::getGetterMethodName($propName);
        if ($beanClass->hasMethod($methodName)) {
            $this->getterMethod = $beanClass->getMethod($methodName);
        }

        if ($this->setterMethod === null and $this->getterMethod === null) {
            throw new seasar::exception::IllegalPropertyRuntimeException($propName);
        }
    }

    /**
     * セッターメソッド名を生成します。
     *
     * @param string $propName
     * @return string
     */
    public static function getSetterMethodName($propName) {
        return 'set' . ucfirst($propName);
    }

    /**
     * ゲッターメソッド名を生成します。
     *
     * @param string $propName
     * @return string
     */
    public static function getGetterMethodName($propName) {
        return 'get' . ucfirst($propName);
    }

    /**
     * ReflectionMethodを返します。
     * @return ReflectionMethod
     */
    public function getSetterMethod() {
        return $this->setterMethod;
    }

    /**
     * @see seasar::beans::AccessorMethodPropertyDesc::getSetterMethod()
     */
    public function getWriteMethod() {
        return $this->getSetterMethod();
    }

    /**
     * ReflectionMethodを設定します。
     * @param ReflectionMethod $method
     */
    public function setSetterMethod(ReflectionMethod $method) {
        $this->setterMethod = $method;
    }

    /**
     * @see seasar::beans::AccessorMethodPropertyDesc::setSetterMethod()
     */
    public function setWriteMethod(ReflectionMethod $method) {
        $this->setSetterMethod($method);
    }

    /**
     * ReflectionMethodを返します。
     * @return ReflectionMethod
     */
    public function getGetterMethod() {
        return $this->getterMethod;
    }

    /**
     * @see seasar::beans::AccessorMethodPropertyDesc::getGetterMethod()
     */
    public function getReadMethod() {
        return $this->getGetterMethod();
    }

    /**
     * ReflectionMethodを設定します。
     * @param ReflectionMethod $method
     */
    public function setGetterMethod(ReflectionMethod $method) {
        $this->getterMethod = $method;
    }

    /**
     * @see seasar::beans::AccessorMethodPropertyDesc::setGetterMethod()
     */
    public function setReadMethod(ReflectionMethod $method) {
        $this->setGetterMethod($method);
    }

    /**
     * @see seasar::beans::AbstractPropertyDesc::setValue()
     */
    public function setValue($instance, $value) {
        if ($this->setterMethod === null) {
            throw new seasar::exception::MethodNotFoundRuntimeException(self::getSetterMethodName($this->propertyName));
        }
        $this->setterMethod->invoke($instance, $value);
    }

    /**
     * @see seasar::beans::AbstractPropertyDesc::getValue()
     */
    public function getValue($instance) {
        if ($this->getterMethod === null) {
            throw new seasar::exception::MethodNotFoundRuntimeException(self::getGetterMethodName($this->propertyName));
        }
        return $this->getterMethod->invoke($instance);
    }

    /**
     * @see seasar::beans::AbstractPropertyDesc::getReflection()
     */
    public function getReflection() {
        return $this->setterMethod;
    }
}
