<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2010 the Seasar Foundation and the Others.            |
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
 * プロパティの定義を行うインタ^フェースです。
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.beans
 * @author    klove
 */
namespace seasar\beans;
interface PropertyDesc {

    /**
     * インスタンスのプロパティ値を設定します。
     * @param object $instance
     * @param mixed $value
     */
    public function setValue($instance, $value);

    /**
     * インスタンスのプロパティ値を返します。
     * @param object $instance
     * @return mixed
     */
    public function getValue($instance);

    /**
     * プロパティのReflectionClassを返します。
     *  - PublicPropertyDescの場合は、プロパティのReflectionProperty
     *  - AccessorMethodPropertyDescの場合は、セッターメソッドのReflectionMethod
     * @return \ReflectionClass|ReflectionProperty|ReflectionMethod
     */
    public function getReflection();

    /**
     * Getterメソッドを持っているかどうかを返します。
     * @return boolean
     */
    public function hasReadMethod();

    /**
     * Setterメソッドを持っているかどうかを返します。
     * @return boolean
     */
    public function hasWriteMethod();

    /**
     * プロパティの値が取得できるかどうかを返します。
     * @return boolean
     */
    public function isReadable();

    /**
     * プロパティの値が設定できるかどうかを返します。
     * @return boolean
     */
    public function isWritable();

    /**
     * \ReflectionClassインスタンスを返します。
     * @return \ReflectionClass
     */
    public function getBeanClass();

    /**
     * プロパティ名を返します。
     * @return string
     */
    public function getPropertyName();

    /**
     * タイプヒントを返します。
     * @return string
     */
    public function getTypehint();

    /**
     * タイプヒントを返します。
     * @return string
     */
    public function setTypehint($value);

    /**
     * タイプヒントを返します。
     * @return \ReflectionClass
     */
    public function getTypehintClass();

    /**
     * タイプヒントを返します。
     * @return \ReflectionClass
     */
    public function setTypehintClass(\ReflectionClass $clazz);

    /**
     * 配列が指定されているかどうかを返します。
     * @return boolean
     */
    public function isArrayAcceptable();

    /**
     * 配列が指定されているかどうかを設定します。
     * @param boolean $value
     */
    public function setArrayAcceptable($value = true);
}
