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
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar\util;
class StringUtilTest extends \PHPUnit_Framework_TestCase {

    public function testMixToString() {
        $this->assertEquals(StringUtil::mixToString('hoge'), 'hoge');
        $this->assertEquals(StringUtil::mixToString(array('hoge','huga')), 'array(2)');
        $this->assertEquals(1, preg_match('/^object\[stdClass#/', StringUtil::mixToString(new \stdClass)));
        print StringUtil::mixToString(new \stdClass) . PHP_EOL;
        $this->assertEquals(StringUtil::mixToString(false), 'false');
        $this->assertEquals(StringUtil::mixToString(null), 'null');
        $this->assertEquals(StringUtil::mixToString(1), '1');
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}


