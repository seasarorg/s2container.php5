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
 * 自動バンディング定義のファクトリです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
class AutoBindingDefFactory {

    /**
     * @var array 
     */
    private static $autoBindingDefs = array();

    /**
     * AutoBindingDefFactoryの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * 自動バインディング定義を追加します。
     *
     * @param \seasar\container\AutoBindingDef $autoBindingDef
     */
    public static function addAutoBindingDef(\seasar\container\AutoBindingDef $autoBindingDef) {
        self::$autoBindingDefs[$autoBindingDef->getName()] = $autoBindingDef;
    }

    /**
     * 自動バインディング定義が存在するかどうかを返します。
     *
     * @param string $name
     * @return boolean
     */
    public static function existAutoBindingDef($name) {
        return array_key_exists($name, self::$autoBindingDefs);
    }

    /**
     * 自動バインディング定義を返します。
     *
     * @param string $name
     * @return object
     */
    public static function getAutoBindingDef($name) {
        if (!self::existAutoBindingDef($name)) {
            throw new \seasar\container\exception\IllegalAutoBindingDefRuntimeException($name);
        }
        return self::$autoBindingDefs[$name];
    }
}

/**
 * デフォルトの自動バインディング定義の追加を行います。
 */
\seasar\container\assembler\AutoBindingDefFactory::addAutoBindingDef(new AutoBindingAutoDef(\seasar\container\AutoBindingDef::AUTO_NAME));
\seasar\container\assembler\AutoBindingDefFactory::addAutoBindingDef(new AutoBindingNoneDef(\seasar\container\AutoBindingDef::NONE_NAME));
