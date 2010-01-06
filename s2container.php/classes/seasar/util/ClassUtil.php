<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2009 the Seasar Foundation and the Others.            |
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
 * \ReflectionClass用のユーティリティクラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar\util;
final class ClassUtil {

    /**
     * ClassUtilの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * クラスが実装しているすべてのインターフェースを返します。
     *
     * @param \ReflectionClass $clazz
     * @return array
     */
    public static function getInterfaces(\ReflectionClass $clazz) {
        $interfaces = array_values($clazz->getInterfaces());
        if ($clazz->isInterface()) {
            $interfaces[] = $clazz;
        }
        return $interfaces;
    }

    /**
     * クラスが実装しているメソッドを返します。
     * 親クラスで実装されているメソッドは含まれません。
     *
     * @param \ReflectionClass $clazz
     * @return array
     */
    public static function getDeclaringMethods(\ReflectionClass $clazz) {
        if ($clazz->getParentClass() === null) {
            return $clazz->getMethods();
        }

        $implMethods = $clazz->getMethods();
        $methods = array();
        $className = $clazz->getName();
        foreach($implMethods as $implMethod) {
            if (!$implMethod->isConstructor() and
                $implMethod->getDeclaringClass()->getName() === $className) {
                $methods[] = $implMethod;
            }
        }
        return $methods;
    }

    /**
     * クラスが実装しているすべてのAbstractメソッドを返します。
     *
     * @param \ReflectionClass $clazz
     * @return array
     */
    public static function getImplementMethods(\ReflectionClass $clazz) {
        if ($clazz->isInterface()) {
            return $clazz->getMethods();
        }

        $methods = array();
        if ($clazz->isAbstract()) {
            $methodRefs = $clazz->getMethods();
            foreach ($methodRefs as $methodRef) {
                if($methodRef->isAbstract()){
                    $methods[] = $methodRef;
                }
            }
        }
        $interfaces = self::getInterfaces($clazz);
        foreach ($interfaces as $interface) {
            $methods = array_merge($methods, $interface->getMethods());
        }
        return $methods;
    }

    /**
     * すべてのAbstractメソッドを返します。
     *
     * @param \ReflectionClass $clazz
     * @return array
     */
    public static function getAbstractMethods(\ReflectionClass $clazz) {
        if ($clazz->isInterface()) {
            return $clazz->getMethods();
        }

        $methods = array();
        if ($clazz->isAbstract()) {
            $methodRefs = $clazz->getMethods();
            foreach ($methodRefs as $methodRef) {
                if($methodRef->isAbstract()){
                    $methods[] = $methodRef;
                }
            }
        }
        return $methods;
    }

    /**
     * \ReflectionClassからインスタンスを生成します。
     *
     * @param \ReflectionClass $reflection
     * @param array $args
     * @return object
     */
    public static function newInstance(\ReflectionClass $reflection, array $args = array()) {
        if (count($args) > 0) {
            return $reflection->newInstanceArgs($args);
        }
        return $reflection->newInstance();
    }

}
