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
namespace seasar\beans;
class AccessorMethodPropertyDesc extends AbstractPropertyDesc {

    /**
     * @var \ReflectionMethod
     */
    private $writeMethod = null;

    /**
     * @var \ReflectionMethod
     */
    private $readMethod = null;

    /**
     * AccessorMethodPropertyDescを構築します。
     *
     * @see \seasar\beans\AbstractPropertyDesc::_construct()
     */
    public function __construct(\ReflectionClass $beanClass, $propName) {
        parent::__construct($beanClass, $propName);
        $methodName = self::getWriteMethodName($propName);
        if ($beanClass->hasMethod($methodName)) {
            $method = $beanClass->getMethod($methodName);
            if ($method->isPublic()) {
                $this->writeMethod = $method;
            }
        }
        $methodName = self::getReadMethodName($propName);
        if ($beanClass->hasMethod($methodName)) {
            $method = $beanClass->getMethod($methodName);
            if ($method->isPublic()) {
                $this->readMethod = $method;
            }
        }

        if ($this->writeMethod === null and $this->readMethod === null) {
            throw new \seasar\exception\IllegalPropertyRuntimeException($propName);
        }
    }

    /**
     * セッターメソッド名を生成します。
     *
     * @param string $propName
     * @return string
     */
    public static function getWriteMethodName($propName) {
        return 'set' . ucfirst($propName);
    }

    /**
     * ゲッターメソッド名を生成します。
     *
     * @param string $propName
     * @return string
     */
    public static function getReadMethodName($propName) {
        return 'get' . ucfirst($propName);
    }

    /**
     * セッター用のReflectionMethodを返します。
     * @return \ReflectionMethod
     */
    public function getWriteMethod() {
        return $this->writeMethod;
    }

    /**
     * セッター用のReflectionMethodを設定します。
     * @param \ReflectionMethod $method
     */
    public function setWriteMethod(\ReflectionMethod $method) {
        $this->writeMethod = $method;
    }

    /**
     * ゲッター用のReflectionMethodを返します。
     * @return \ReflectionMethod
     */
    public function getReadMethod() {
        return $this->readMethod;
    }

    /**
     * ゲッター用のReflectionMethodを設定します。
     * @param \ReflectionMethod $method
     */
    public function setReadMethod(\ReflectionMethod $method) {
        $this->readMethod = $method;
    }

    /**
     * @see \seasar\beans\PropertyDesc::hasReadMethod()
     */
    public function hasReadMethod() {
        return $this->readMethod instanceof \ReflectionMethod;
    }

    /**
     * @see \seasar\beans\PropertyDesc::hasWriteMethod()
     */
    public function hasWriteMethod() {
        return $this->writeMethod instanceof \ReflectionMethod;
    }

    /**
     * @see \seasar\beans\PropertyDesc::isReadable()
     */
    public function isReadable() {
        return $this->readMethod instanceof \ReflectionMethod;
    }

    /**
     * @see \seasar\beans\PropertyDesc::isWritable()
     */
    public function isWritable() {
        return $this->writeMethod instanceof \ReflectionMethod;
    }

    /**
     * @see \seasar\beans\AbstractPropertyDesc::setValue()
     */
    public function setValue($instance, $value) {
        if ($this->writeMethod === null) {
            throw new \seasar\exception\MethodNotFoundRuntimeException(self::getWriteMethodName($this->propertyName));
        }
        $this->writeMethod->invoke($instance, $value);
    }

    /**
     * @see \seasar\beans\AbstractPropertyDesc::getValue()
     */
    public function getValue($instance) {
        if ($this->readMethod === null) {
            throw new \seasar\exception\MethodNotFoundRuntimeException(self::getReadMethodName($this->propertyName));
        }
        return $this->readMethod->invoke($instance);
    }

    /**
     * @see \seasar\beans\AbstractPropertyDesc::getReflection()
     */
    public function getReflection() {
        return $this->writeMethod;
    }
}
