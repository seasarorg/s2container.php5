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
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.log
 * @author    klove
 */
namespace seasar\log;
class SimpleLoggerTest extends \PHPUnit_Framework_TestCase {

    public function testStdout() {
        S2Logger::getInstance(__CLASS__)->debug('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->info('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->notice('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->warn('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->error('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->fatal('called.', __METHOD__);
    }

    public function testFile() {
        \seasar\Config::$SIMPLE_LOG_FILE = dirname(__FILE__) . DIRECTORY_SEPARATOR . 's2.log';
        S2Logger::getInstance(__CLASS__)->debug('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->info('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->notice('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->warn('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->error('called.', __METHOD__);
        S2Logger::getInstance(__CLASS__)->fatal('called.', __METHOD__);
        unlink(\seasar\Config::$SIMPLE_LOG_FILE);
        \seasar\Config::$SIMPLE_LOG_FILE = null;
    }

    public function testWithoutMethodName() {
        S2Logger::getInstance()->debug('called.');
        S2Logger::getInstance()->info('called.');
        S2Logger::getInstance()->notice('called.');
        S2Logger::getInstance()->warn('called.');
        S2Logger::getInstance()->error();
        S2Logger::getInstance()->fatal();
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

