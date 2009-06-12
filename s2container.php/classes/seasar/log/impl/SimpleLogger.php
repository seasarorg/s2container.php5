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
 * シンプルなLoggerクラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.log.impl
 * @author    klove
 */
namespace seasar\log\impl;
class SimpleLogger {
    const DEBUG  = 1;
    const INFO   = 2;
    const NOTICE = 3;
    const WARN   = 4;
    const ERROR  = 5;
    const FATAL  = 6;

    /**
     * @var array
     */
    private $labels = array(
                          self::DEBUG  => 'DEBUG',
                          self::INFO   => 'INFO',
                          self::NOTICE => 'NOTICE',
                          self::WARN   => 'WARN',
                          self::ERROR  => 'ERROR',
                          self::FATAL  => 'FATAL');

    /**
     * DEBUGレベルのログを出力します。
     *
     * @param string log message
     * @param string method name
     */
    public function debug($msg = '-', $methodName = '-') {
        $this->logging(self::DEBUG, $msg, $methodName);
    }

    /**
     * INFOレベルのログを出力します。
     *
     * @param string log message
     * @param string method name
     */
    public function info($msg = '-', $methodName = '-') {
        $this->logging(self::INFO, $msg, $methodName);
    }

    /**
     * NOTICEレベルのログを出力します。
     *
     * @param string log message
     * @param string method name
     */
    public function notice($msg = '-', $methodName = '-') {
        $this->logging(self::NOTICE, $msg, $methodName);
    }

    /**
     * WARNレベルのログを出力します。
     *
     * @param string log message
     * @param string method name
     */
    public function warn($msg = '-', $methodName = '-') {
        $this->logging(self::WARN, $msg, $methodName);
    }

    /**
     * ERRORレベルのログを出力します。
     *
     * @param string log message
     * @param string method name
     */
    public function error($msg = '-', $methodName = '-') {
        $this->logging(self::ERROR, $msg, $methodName);
    }

    /**
     * FATALレベルのログを出力します。
     *
     * @param string log message
     * @param string method name
     */
    public function fatal($msg = '-', $methodName = '-') {
        $this->logging(self::FATAL, $msg, $methodName);
    }

    /**
     * ログを標準出力します。
     * \seasar\Config::$SIMPLE_LOG_FILE が設定されている場合は、ファイル出力を行います。
     *
     * @param integer log level
     * @param string log message
     * @param string method name
     */
    private function logging($level, $msg, $methodName) {
        if (\seasar\Config::$LOG_LEVEL <= $level) {
            $logMsg = sprintf('%s [%-6s] %s - %s' . PHP_EOL, date('Y-m-d H:i:s'), $this->labels[$level], $methodName, $msg);
            if (\seasar\Config::$SIMPLE_LOG_FILE !== null) {
                file_put_contents(\seasar\Config::$SIMPLE_LOG_FILE, $logMsg, FILE_APPEND | LOCK_EX);
            } else {
                print $logMsg;
            }
        }
    }
}
