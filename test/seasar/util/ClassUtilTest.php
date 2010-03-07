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
 * @since     Class available since Release 2.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar\util;
class ClassUtilTest extends \PHPUnit_Framework_TestCase {

    public function testGetInterfaces() {
        $refClass = new \ReflectionClass(__NAMESPACE__ . '\A_ClassUtil');
        $is = ClassUtil::getInterfaces($refClass);
        $this->assertEquals(0, count($is));

        $refClass = new \ReflectionClass(__NAMESPACE__ . '\IA_ClassUtil');
        $is = ClassUtil::getInterfaces($refClass);
        $this->assertEquals(1, count($is));
        $this->assertTrue($is[0] instanceof \ReflectionClass);

        $refClass = new \ReflectionClass(__NAMESPACE__ . '\IC_ClassUtil');
        $is = ClassUtil::getInterfaces($refClass);
        $this->assertEquals(2, count($is));

        $refClass = new \ReflectionClass(__NAMESPACE__ . '\IE_ClassUtil');
        $is = ClassUtil::getInterfaces($refClass);
        $this->assertEquals(5, count($is));

        $refClass = new \ReflectionClass(__NAMESPACE__ . '\B_ClassUtil');
        $is = ClassUtil::getInterfaces($refClass);
        $this->assertEquals(5, count($is));
    }

    public function testGetDeclaringMethods() {
        $refClass = new \ReflectionClass(__NAMESPACE__ . '\C_ClassUtil');
        $methods = ClassUtil::getDeclaringMethods($refClass);
        $this->assertEquals(2, count($methods));

        $refClass = new \ReflectionClass(__NAMESPACE__ . '\D_ClassUtil');
        $methods = ClassUtil::getDeclaringMethods($refClass);
        $this->assertEquals(2, count($methods));
    }

    public function testImplementMethods() {
        $refClass = new \ReflectionClass(__NAMESPACE__ . '\F_ClassUtil');
        $methods = ClassUtil::getImplementMethods($refClass);
        $this->assertEquals(2, count($methods));

        $refClass = new \ReflectionClass(__NAMESPACE__ . '\G_ClassUtil');
        $methods = ClassUtil::getImplementMethods($refClass);
        $this->assertEquals(6, count($methods));
    }

    public function testAbstractMethods() {
        $refClass = new \ReflectionClass(__NAMESPACE__ . '\F_ClassUtil');
        $methods = ClassUtil::getAbstractMethods($refClass);
        $this->assertEquals(2, count($methods));

        $refClass = new \ReflectionClass(__NAMESPACE__ . '\G_ClassUtil');
        $methods = ClassUtil::getAbstractMethods($refClass);
        $this->assertEquals(3, count($methods));
    }

    public function testNewInstance() {
        $className = __NAMESPACE__ . '\H_ClassUtil';
        $refClass = new \ReflectionClass($className);
        $this->assertTrue(ClassUtil::newInstance($refClass) instanceof $className);

        $className = __NAMESPACE__ . '\I_ClassUtil';
        $refClass = new \ReflectionClass($className);
        $this->assertTrue(ClassUtil::newInstance($refClass, array(1, 2)) instanceof $className);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_ClassUtil {}

interface IA_ClassUtil {}
interface IB_ClassUtil {}

interface IC_ClassUtil extends IA_ClassUtil {}

interface ID_ClassUtil extends IC_ClassUtil {}

interface IE_ClassUtil extends ID_ClassUtil, IB_ClassUtil {}

class B_ClassUtil implements IE_ClassUtil {}

class C_ClassUtil {
    public function __construct(){}
    public function a(){}
    private function b(){}
}

class D_ClassUtil extends C_ClassUtil {
    public function __construct(){}
    public function c(){}
    private function d(){}
}

interface E_ClassUtil {
    public function e();
}

interface F_ClassUtil extends E_ClassUtil {
    public function f();
}

abstract class G_ClassUtil implements F_ClassUtil {

    public abstract function g();
}

class H_ClassUtil {}
class I_ClassUtil {
    public $a = null;
    public $b = null;
    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }
}

