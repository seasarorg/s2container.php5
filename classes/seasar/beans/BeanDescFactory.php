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
 * BeanDescを生成するファクトリクラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.beans
 * @author    klove
 */
namespace seasar\beans;
class BeanDescFactory {

    /**
     * @var array
     */
    private static $spool = array();

    /**
     * BeanDescFactoryの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * BeanDescを生成します。
     *
     * @param string|object|ReflectionClass $obj
     * @return \seasar\beans\BeanDesc
     */
    public static function create($obj) {
        if ($obj instanceof \ReflectionClass) {
            $clazz = $obj;
            $className = $clazz->getName();
        } else if (is_object($obj)) {
            $clazz = new \ReflectionClass($obj);
            $className = $clazz->getName();
        } else {
            $clazz = new \ReflectionClass($obj);
            $className = $obj;
        }

        if (!isset(self::$spool[$className])) {
            self::$spool[$className] = new BeanDesc($clazz);
        }
        return self::$spool[$className];
    }

    /**
     * @see \seasar\beans\BeanDescFactory::create()
     */
    public static function getBeanDesc($value) {
        return self::create($value);
    }
}
