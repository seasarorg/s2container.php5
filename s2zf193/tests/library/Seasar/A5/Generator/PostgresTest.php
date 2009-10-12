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
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5
 * @author    klove
 */

use \seasar\container\S2ApplicationContext as s2app;
require_once(dirname(APPLICATION_PATH) . '/library/Seasar/A5.php');

class Seasar_A5_Generator_PostgresTest extends PHPUnit_Framework_TestCase {

    public function testGen() {
        $a5erFile = dirname(dirname(__FILE__)) . '/test_postgres.a5er';
        $schema = $this->parser->parse($a5erFile);
        $saveDir = dirname(__FILE__) . '/tmp';
        $this->generator->gen($schema, $saveDir);
    }

    public function setUp() {
        s2app::init();
        require(dirname(APPLICATION_PATH) . '/library/Seasar/A5/config/postgres.php');
        $this->parser = s2app::get('Seasar_A5_Parser');
        $this->generator = s2app::get('Seasar_A5_Generator_Postgres');
    }

    public function tearDown() {
        $this->parser = null;
        $this->generator = null;
    }
}


