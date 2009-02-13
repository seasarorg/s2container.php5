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
 * @package   seasar.beans
 * @author    klove
 */
namespace seasar\beans;
class PublicPropertyDescTest extends \PHPUnit_Framework_TestCase {

    public function testSetValue() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_PublicPropertyDescTest');
        $desc = new PublicPropertyDesc($ref, 'name');
        $obj = new A_PublicPropertyDescTest;
        $desc->setValue($obj, 'seasar');
        $this->assertEquals($obj->name, 'seasar');
        $this->assertEquals($desc->getValue($obj), 'seasar');
    }

    public function testPropertNotFound() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_PublicPropertyDescTest');
        try {
            $desc = new PublicPropertyDesc($ref, 'xxx');
            $this->fail();
        } catch (\seasar\exception\PropertyNotFoundRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testIllegalPropertFound() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_PublicPropertyDescTest');
        try {
            $desc = new PublicPropertyDesc($ref, 'yyy');
            $this->fail();
        } catch (\seasar\exception\IllegalPropertyRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_PublicPropertyDescTest {
    public $name = null;
    private $yyy = '';
}
