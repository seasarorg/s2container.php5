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
 * ReflectionClass用のユーティリティクラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar::util;
final class ClassUtil {

    /**
     * ClassUtilの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * クラスが実装しているすべてのインターフェースを返します。
     *
     * @param ReflectionClass $clazz
     * @return array
     */
    public static function getInterfaces(ReflectionClass $clazz) {
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
     * @param ReflectionClass $clazz
     * @return array
     */
    public function getDeclaringMethods(ReflectionClass $clazz) {
        if ($clazz->getParentClass() === null) {
            return $clazz->getMethods();
        }

        $implMethods = $clazz->getMethods();
        $o = count($implMethods);
        $methods = array();
        $className = $clazz->getName();
        for ($i=0; $i<$o; $i++) {
            if (!$implMethods[$i]->isConstructor() and
                $implMethods[$i]->getDeclaringClass()->getName() === $className) {
                $methods[] = $implMethods[$i];
            }
        }
        return $methods;
    }

    /**
     * クラスが実装しているすべてのAbstractメソッドを返します。
     *
     * @param ReflectionClass $clazz
     * @return array
     */
    public function getImplementMethods(ReflectionClass $clazz) {
        if ($clazz->isInterface()) {
            return $clazz->getMethods();
        }

        $methods = array();
        if ($clazz->isAbstract()) {
            $methodRefs = $clazz->getMethods();
            $o = count($methodRefs);
            for ($j = 0; $j < $o; $j++) {
                if($methodRefs[$j]->isAbstract()){
                    $methods[] = $methodRefs[$j];
                }
            }
        }
        $interfaces = self::getInterfaces($clazz);
        $o = count($interfaces);
        for ($i = 0; $i < $o; $i++) {
            $methods = array_merge($methods, $interfaces[$i]->getMethods());
        }
        return $methods;
    }

    /**
     * すべてのAbstractメソッドを返します。
     *
     * @param ReflectionClass $clazz
     * @return array
     */
    public function getAbstractMethods(ReflectionClass $clazz) {
        if ($clazz->isInterface()) {
            return $clazz->getMethods();
        }

        $methods = array();
        if ($clazz->isAbstract()) {
            $methodRefs = $clazz->getMethods();
            $o = count($methodRefs);
            for ($j = 0; $j < $o; $j++) {
                if($methodRefs[$j]->isAbstract()){
                    $methods[] = $methodRefs[$j];
                }
            }
        }
        return $methods;
    }

    /**
     * ReflectionClassからインスタンスを生成します。
     *
     * @param ReflectionClass $reflection
     * @param array $args
     * @return object
     */
    public static function newInstance(ReflectionClass $reflection, array $args) {
        if (count($args) > 0) {
            return $reflection->newInstanceArgs($args);
        }
        return $reflection->newInstance();
    }

    /**
     * ネームスペースを含まないクラス名を返します。
     *
     * @param string $className
     * @return string
     */
    public static function getClassName($className) {
        $items = explode('::', $className);
        return array_pop($items);
    }

    /**
     * クラス名を含まないネームスペースを返します。
     *
     * @param string $className
     * @return string
     */
    public static function getNamespace($className) {
        $items = explode('::', $className);
        array_pop($items);
        return implode('::', $items);
    }
}
