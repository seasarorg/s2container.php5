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
 * InstanceDefを作成するためのクラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.deployer
 * @author    klove
 */
namespace seasar::container::deployer;
class InstanceDefFactory {

    /**
     * @var array
     */
    private static $instanceDefs = array();

    /**
     * InstanceDefを追加します。
     */
    private function __construct() {}

    /**
     * InstanceDefを作成するためのクラスです。
     *
     * @param seasar::container::InstanceDef $instanceDef
     */
    public static function addInstanceDef(seasar::container::InstanceDef $instanceDef) {
        self::$instanceDefs[$instanceDef->getName()] = $instanceDef;
    }

    /**
     * InstanceDefが存在するかどうかを返します。
     *
     * @param string $name
     */
    public static function existInstanceDef($name) {
        return array_key_exists($name, self::$instanceDefs);
    }

    /**
     * nameに応じたInstanceDefを返します。
     *
     * @param string $name
     */
    public static function getInstanceDef($name) {
        if (!self::existInstanceDef($name)) {
            throw new seasar::container::exception::IllegalInstanceDefRuntimeException($name);
        }
        return self::$instanceDefs[$name];
    }
}

/**
 * デフォルトのInstanceDefを登録します。
 */
seasar::container::deployer::InstanceDefFactory::addInstanceDef(new InstanceSingletonDef(seasar::container::InstanceDef::SINGLETON_NAME));
seasar::container::deployer::InstanceDefFactory::addInstanceDef(new InstancePrototypeDef(seasar::container::InstanceDef::PROTOTYPE_NAME));
