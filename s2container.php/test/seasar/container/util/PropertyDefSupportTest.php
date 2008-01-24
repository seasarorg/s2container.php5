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
namespace seasar::container::util;
class PropertyDefSupportTest extends ::PHPUnit_Framework_TestCase {
    public function __construct($name) {
        parent::__construct($name);
    }

    public function testAddPropertyDef() {
        $support = new PropertyDefSupport;
        $this->assertEquals($support->getPropertyDefSize() , 0);

        $support->addPropertyDef(new seasar::container::impl::PropertyDef('name', 'hoge'));
        $support->addPropertyDef(new seasar::container::impl::PropertyDef('year', 2007));
        $this->assertEquals($support->getPropertyDefSize() , 2);
    }

    public function testGetPropertyDef() {
        $support = new PropertyDefSupport;
        $support->addPropertyDef(new seasar::container::impl::PropertyDef('name'));
        $this->assertTrue($support->getPropertyDef(0) instanceof seasar::container::impl::PropertyDef);

        $support->addPropertyDef(new seasar::container::impl::PropertyDef('year', 2007));
        $this->assertTrue($support->getPropertyDef('year')->getValue() == 2007);
    }

    public function testOutOfRange() {
        $support = new PropertyDefSupport;
        $support->addPropertyDef(new seasar::container::impl::PropertyDef('name'));
        try {
            $support->getPropertyDef(1);
            $this->fail();
        } catch(OutOfRangeException $e) {
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

class AnnoTestA_PropertyDefSupportTestTest {
    public static function setSetA($a){}
    public static function a(){}
}

/**
 * @S2Component('name' => 'b')
 */
class AnnoTestB_PropertyDefSupportTestTest {

    /**
     * @S2Binding('1000')
     */
    public static function setSetB($b){}

    /**
     * @S2Binding('abc')
     */
    public static function a(){}

    /**
     * @S2Property('dao.interceptor')
     */
    public static function b(){}
}

class AnnoTestC_PropertyDefSupportTestTest {
    /**
     * @S2Property('interceptor' => 'new seasar::aop::interceptor::TraceInterceptor')
     */
    public function hoge(){}
}

