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

class Seasar_A5_ParserTest extends PHPUnit_Framework_TestCase {

    public function testParse() {
        $a5erFile = dirname(__FILE__) . '/test_sqlite.a5er';
        $schema = $this->parser->parse($a5erFile);

        $this->assertTrue($schema instanceof Seasar_A5_Schema);
        $this->assertEquals(5, count($schema->getEntities()));
        $this->assertEquals(4, count($schema->getRelations()));

        $pname = 'busyo';
        $entity = $schema->getEntity($pname);
        $this->assertTrue($entity instanceof Seasar_A5_Entity);
        $entity = $schema->getEntityByPname($pname);
        $this->assertTrue($entity instanceof Seasar_A5_Entity);

        $lname = '部署';
        $entity = $schema->getEntityByLname($lname);
        $this->assertTrue($entity instanceof Seasar_A5_Entity);

        $className = 'Busyo';
        $entity = $schema->getEntityByClass($className);
        $this->assertTrue($entity instanceof Seasar_A5_Entity);

        //var_dump($schema);

    }

    public function setUp() {
        require_once(dirname(APPLICATION_PATH) . '/library/Seasar/A5.php');
        require_once(dirname(APPLICATION_PATH) . '/library/Seasar/A5/config/sqlite.php');
        s2app::init();
        $this->parser = s2app::get('Seasar_A5_Parser');
    }

    public function tearDown() {
        $this->parser = null;
    }
}


