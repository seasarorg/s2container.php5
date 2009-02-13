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
class AccessorMethodPropertyDescTest extends \PHPUnit_Framework_TestCase {

    public function testSetter() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_AccessorMethodPropertyDescTest');
        $desc = new AccessorMethodPropertyDesc($ref, 'name');
        $obj = new A_AccessorMethodPropertyDescTest;
        $desc->setValue($obj, 'seasar');
        $this->assertEquals($obj->name, 'seasar');
        //$this->assertEquals($desc->getValue($obj), 'seasar');
    }

    public function testGetter() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_AccessorMethodPropertyDescTest');
        $desc = new AccessorMethodPropertyDesc($ref, 'zzz');
        $obj = new A_AccessorMethodPropertyDescTest;
        $this->assertEquals($desc->getValue($obj), 'hoge');
    }

    public function testAccessor() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_AccessorMethodPropertyDescTest');
        $desc = new AccessorMethodPropertyDesc($ref, 'yyy');
        $obj = new A_AccessorMethodPropertyDescTest;
        $desc->setValue($obj, 'seasar');
        $this->assertEquals($desc->getValue($obj), 'seasar');
    }

    public function testIllegalPropertFound() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_AccessorMethodPropertyDescTest');
        try {
            $desc = new AccessorMethodPropertyDesc($ref, 'xxx');
            $this->fail();
        } catch (\seasar\exception\IllegalPropertyRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testSetterNotFound() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_AccessorMethodPropertyDescTest');
        $desc = new AccessorMethodPropertyDesc($ref, 'zzz');
        $obj = new A_AccessorMethodPropertyDescTest;
        try {
            $desc->setValue($obj, 'hoge');
            $this->fail();
        } catch (\seasar\exception\MethodNotFoundRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testGetterNotFound() {
        $ref = new \ReflectionClass(__NAMESPACE__ . '\A_AccessorMethodPropertyDescTest');
        $desc = new AccessorMethodPropertyDesc($ref, 'name');
        $obj = new A_AccessorMethodPropertyDescTest;
        try {
            $desc->getValue($obj);
            $this->fail();
        } catch (\seasar\exception\MethodNotFoundRuntimeException $e) {
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

class A_AccessorMethodPropertyDescTest {
    public $name = null;
    public function setName($name) {
        $this->name = $name;
    }

    private $yyy = '';
    public function setYyy($value) {
        $this->yyy = $value;
    }
    public function getYyy() {
        return $this->yyy;
    }

    private $zzz = 'hoge';
    public function getZzz() {
        return $this->zzz;
    }

    private $xxx = null;
}
