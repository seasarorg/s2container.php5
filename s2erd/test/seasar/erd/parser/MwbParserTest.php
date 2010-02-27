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
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\parser
 * @author    klove
 */
namespace seasar\erd\parser;
use seasar\container\S2ApplicationContext as s2app;
class MwbParserTest extends \PHPUnit_Framework_TestCase {

    public function testParse() {
        $schemaName = 'mydb';
        $this->parser->setSchemaName($schemaName);
        $this->parser->setErdFile(S2ERD_ROOT_DIR . '/test/data/mwb/project.mwb');
        $schema = $this->parser->parse();
        $this->assertTrue($schema instanceof \seasar\erd\model\Schema);

        $this->assertEquals(5, count($schema->getEntities()));
        $entity = $schema->getEntity('employee');
        $this->assertTrue($entity instanceof \seasar\erd\model\Entity);
        $this->assertEquals(7, count($entity->getFields()));
    }

    public function setUp(){
        s2app::init();
        $this->parser = s2app::get('seasar\erd\parser\MwbParser');
    }

    public function tearDown() {
    }
}


