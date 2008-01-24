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
 * ログ出力を提供するクラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.log
 * @author    klove
 */
namespace seasar::log;
class S2Logger {

    /**
     * @var seasar::log::LoggerFactory
     */
    private static $loggerFactory = null;

    /**
     * S2Loggerの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * Loggerのファクトリクラスを設定します。
     * @param seasar::log::LoggerFactory $factory
     */
    public static function setLoggerFactory(seasar::log::LoggerFactory $factory) {
        self::$loggerFactory = $factory;
    }

    /**
     * @see seasar::log::S2Logger::getLogger()
     */
    public static function getInstance($className = null) {
        return self::getLogger($className);
    }

    /**
     * Loggerファクトリを使用してLoggerを返します。
     *
     * @param string $className
     * @return object
     */
    public static function getLogger($className = null) {
        return self::getLoggerFactory()->getInstance($className);
    }

    /**
     * SingletonなLoggerFactoryを返します。
     *
     * @return seasar::log::LoggerFactory
     */
    public static function getLoggerFactory() {
        if (self::$loggerFactory === null) {
            self::$loggerFactory = new seasar::Config::$LOGGER_FACTORY;
        }
        return self::$loggerFactory;
    }
}
