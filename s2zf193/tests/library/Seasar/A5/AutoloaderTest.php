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

class Seasar_A5_AutoloaderTest extends PHPUnit_Framework_TestCase {

    public function testAutoLoad() {
        $className = 'Model_DbTable_PgBusyo';
        $this->assertFalse(class_exists($className, false));
        $this->loader->autoload($className);
        $this->assertTrue(class_exists($className, false));
    }

    public function setUp() {
        require_once(dirname(APPLICATION_PATH) . '/library/Seasar/A5.php');
        s2app::init();
        s2app::register('Seasar_A5_Parser');
        s2app::register('Seasar_A5_Generator_Postgres');
        s2app::register('Seasar_A5_Writer_Dynamic');
        s2app::register('Seasar_A5_Autoloader')->setConstructClosure(function() {
            $loader = new Seasar_A5_Autoloader;
            $loader->setA5erFile(dirname(__FILE__) . '/test_postgres.a5er');
            return $loader;
        });
        $this->loader = s2app::get('Seasar_A5_Autoloader');
    }

    public function tearDown() {
        $this->loader = null;
    }
}


