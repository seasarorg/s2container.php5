<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
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
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar::aop;
class PointcutTest extends ::PHPUnit_Framework_TestCase {

    public function testIsAppliedReflection() {
        $pc = new Pointcut(new ReflectionClass('seasar::aop::AW_Pointcut'));
        $this->assertTrue($pc->isApplied('awm1'));

        $pc = new Pointcut(new ReflectionClass('seasar::aop::C_Pointcut'));
        $this->assertTrue($pc->isApplied('say') === true);

        $pc = new Pointcut(new ReflectionClass('seasar::aop::D_Pointcut'));
        $this->assertTrue($pc->isApplied('say') === false);
        $this->assertTrue($pc->isApplied('hello') === true);

        $pc = new Pointcut(new ReflectionClass('seasar::aop::E_Pointcut'));
        $this->assertFalse($pc->isApplied('setHoge'));
        $this->assertFalse($pc->isApplied('getHoge'));
        $this->assertFalse($pc->isApplied('isHoge'));
        $this->assertTrue($pc->isApplied('setupHoge'));
    }

    public function testIsAppliedRegext() {
        $pc = new Pointcut('/pm1/');
        $this->assertTrue($pc->isApplied('pm1'));
        $this->assertFalse($pc->isApplied('pm2'));

        $pc = new Pointcut('/^pm1$/');
        $this->assertTrue($pc->isApplied('pm1'));
        $this->assertFalse($pc->isApplied('pm2'));

        $pc = new Pointcut('/^a/');
        $this->assertTrue($pc->isApplied('abs'));
        $this->assertFalse($pc->isApplied('deb'));

        $pc = new Pointcut('/b$/');
        $this->assertFalse($pc->isApplied('abs'));
        $this->assertTrue($pc->isApplied('deb'));

        $pc = new Pointcut('/^(!?a)/');
        $this->assertTrue($pc->isApplied('abs'));
        $this->assertFalse($pc->isApplied('deb'));
        $this->assertFalse($pc->isApplied('om'));

        $pc = new Pointcut('/(!?a)$/');
        $this->assertFalse($pc->isApplied('abs'));
        $this->assertTrue($pc->isApplied('aba'));
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}
interface IO_Pointcut {
    function om1();
    function om2();
}

interface IW_Pointcut 
    extends IO_Pointcut {
    function wm1($arg1=null,IA &$a);
    function wm2();
}

abstract class AW_Pointcut 
    implements IW_Pointcut{

    function om1(){
        print __METHOD__ . " called.\n";
    }

    function om2(){
        print __METHOD__ . " called.\n";
    }

    public function wm1($arg1,IA &$a){
        print __METHOD__ . " called.\n";
    }

    function wm2(){
        print __METHOD__ . " called.\n";
    }

    abstract function awm1();
}

class C_Pointcut {
    private $name;
    function __construct($name) {
        $this->name =$name;
    }

    public function say(){
        return $this->name;
    }
}

class D_Pointcut extends C_Pointcut {
    public function hello(){
    }
}

class E_Pointcut {
    public function setHoge(){}
    public function getHoge(){}
    public function isHoge(){}
    public function setupHoge(){}
}
