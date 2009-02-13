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
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.util
 * @author    klove
 */
namespace seasar\container\util;
class ArgDefSupportTest extends \PHPUnit_Framework_TestCase {

    public function testAddArgDef() {
        $support = new ArgDefSupport;
        $this->assertEquals($support->getArgDefSize() , 0);

        $support->addArgDef(new \seasar\container\impl\ArgDef);
        $support->addArgDef(new \seasar\container\impl\ArgDef);
        $this->assertEquals($support->getArgDefSize() , 2);
    }

    public function testGetArgDef() {
        $support = new ArgDefSupport;
        $support->addArgDef(new \seasar\container\impl\ArgDef);
        $this->assertTrue($support->getArgDef(0) instanceof \seasar\container\impl\ArgDef);
    }

    public function testOutOfRange() {
        $support = new ArgDefSupport;
        $support->addArgDef(new \seasar\container\impl\ArgDef);
        try {
            $support->getArgDef(1);
            $this->fail();
        } catch(\OutOfRangeException $e) {
            print $e->getMessage();
        } catch (Exception $e) {
            print $e->getMessage();
            $this->fail();
        }
    }
    
    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }

}

class AnnoTestA_ArgDefSupportTestTest {
    public static function setSetA($a){}
    public static function a(){}
}

/**
 * @S2Component('name' => 'b')
 */
class AnnoTestB_ArgDefSupportTestTest {

    /**
     * @S2Binding('1000')
     */
    public static function setSetB($b){}

    /**
     * @S2Binding('abc')
     */
    public static function a(){}

    /**
     * @S2Aspect('dao.interceptor')
     */
    public static function b(){}
}

class AnnoTestC_ArgDefSupportTestTest {
    /**
     * @S2Aspect('interceptor' => 'new \seasar\aop\interceptor\TraceInterceptor')
     */
    public function hoge(){}
}

