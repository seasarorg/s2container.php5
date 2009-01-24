<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.util
 * @author    klove
 */
namespace seasar\container\util;
class ConstructorUtilTest extends \PHPUnit_Framework_TestCase {

    public function testGetInstanceWithArgs() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl('\seasar\container\util\A_ConstructorUtilTest');
        $obj = ConstructorUtil::getInstance($componentDef);
        $this->assertTrue($obj instanceof \seasar\container\util\A_ConstructorUtilTest);
    }

    public function testGetInstanceWithoutArgs() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl('\seasar\container\util\B_ConstructorUtilTest');
        $obj = ConstructorUtil::newInstance($componentDef, array('hoge', 2007));
        $this->assertEquals($obj->name, 'hoge');
        $this->assertEquals($obj->year, 2007);
    }

    public function testGetInstanceNoConstructor() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl('\seasar\container\util\C_ConstructorUtilTest');
        $obj = ConstructorUtil::newInstance($componentDef);
        $this->assertTrue($obj instanceof \seasar\container\util\C_ConstructorUtilTest);
    }

    public function testIllegalConstructor() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl('\seasar\container\util\C_ConstructorUtilTest');
        try {
            ConstructorUtil::newInstance($componentDef, array('hoge', 2007));
            $this->fail();
        } catch (\seasar\container\exception\IllegalConstructorRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
            $this->fail();
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_ConstructorUtilTest {
    public function __construct() {}
}

class B_ConstructorUtilTest {
    public $name = null;
    public $year = null;
    public function __construct($name, $year) {
        $this->name = $name;
        $this->year = $year;
    }
}

class C_ConstructorUtilTest {}
