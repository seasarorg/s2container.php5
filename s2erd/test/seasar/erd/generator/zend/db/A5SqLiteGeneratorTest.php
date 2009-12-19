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
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\generator\zen\db
 * @author    klove
 */
namespace seasar\erd\generator\zen\db;
use seasar\container\S2ApplicationContext as s2app;
class A5SqLiteGeneratorTest extends \PHPUnit_Framework_TestCase {

    public function testGenerate() {
        $this->parser->setErdFile(S2ERD_ROOT_DIR . '/test/data/a5/sqlite.a5er');
        $schema = $this->parser->parse();
        $this->generator->generate($schema);
    }

    public function setUp(){
        s2app::init();
        s2component('seasar\erd\writer\FileWriter');
        $this->parser = s2app::get('seasar\erd\parser\A5Parser');
        $this->generator = s2app::get('seasar\erd\generator\zend\db\SqLiteGenerator');
        $this->generator->setModelClassTplFile(S2ERD_ROOT_DIR . '/test/data/tpl/model.tpl');
        $this->generator->setModelSuperClassTplFile(S2ERD_ROOT_DIR . '/test/data/tpl/model_abstract.tpl');
        $this->generator->setSaveDir(dirname(__FILE__) . '/tmp_a5_sqlite');
    }

    public function tearDown() {
    }
}


