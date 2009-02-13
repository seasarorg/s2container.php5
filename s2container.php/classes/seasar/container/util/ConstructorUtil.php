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
 * Constructor用のユーティリティクラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.util
 * @author    klove
 */
namespace seasar\container\util;
final class ConstructorUtil {

    /**
     * ConstructorUtilの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * 新しいインスタンスを作成します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     * @param array $args
     */
    public static function newInstance(\seasar\container\ComponentDef $componentDef, array $args = array()) {
        if ($componentDef->getAspectDefSize() > 0) {
            return \seasar\container\util\AopUtil::getInstance($componentDef, $args);
        }
        $reflection = $componentDef->getComponentClass();
        if ($reflection->getConstructor() instanceof \ReflectionMethod) {
            return $reflection->newInstanceArgs($args);
        }
        if (count($args) > 0) {
            throw new \seasar\container\exception\IllegalConstructorRuntimeException($reflection);
        }
        return $reflection->newInstance();
    }

    /**
     * 新しいインスタンスを作成します。
     *
     * @see \seasar\container\util\ConstructorUtil::newInstance()
     * @param \seasar\container\ComponentDef $componentDef
     * @param array $args
     */
    public static function getInstance(\seasar\container\ComponentDef $componentDef, array $args = array()) {
        return self::newInstance($componentDef, $args);
    }
}
