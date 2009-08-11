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
 * @since     Class available since Release 2.0.1
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
class ConstructClosureAssemblerTest extends \PHPUnit_Framework_TestCase {

    public function testAssemble() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_ConstructClosureAssemblerTest');
        $assembler = new ConstructClosureAssembler($componentDef);
        $this->assertTrue($assembler->assemble() instanceof \seasar\container\assembler\A_ConstructClosureAssemblerTest);
    }

    public function testClosure() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\B_ConstructClosureAssemblerTest');
        $arg = 'abc';
        $componentDef->setConstructClosure(function($cd) use($arg) { return $cd->getComponentClass()->newInstance($arg);} );
        $assembler = new ConstructClosureAssembler($componentDef);
        $component = $assembler->assemble();
        $this->assertTrue($component instanceof \seasar\container\assembler\B_ConstructClosureAssemblerTest);
        $this->assertEquals($arg, $component->a);
    }

    public function testClosureClass() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl('\Closure');
        $arg = 'abc';
        $clazz = __NAMESPACE__ . '\B_ConstructClosureAssemblerTest';
        $componentDef->setConstructClosure(function($cd) use($arg, $clazz) { return new $clazz($arg);} );
        $assembler = new ConstructClosureAssembler($componentDef);
        $component = $assembler->assemble();
        $this->assertTrue($component instanceof \Closure);
        $obj = $component(1);
        $this->assertTrue($obj instanceof \seasar\container\assembler\B_ConstructClosureAssemblerTest);
        $this->assertEquals($arg, $obj->a);
    }

    public function testUnmatch() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_ConstructClosureAssemblerTest');
        $arg = 'abc';
        $clazz = __NAMESPACE__ . '\B_ConstructClosureAssemblerTest';
        $componentDef->setConstructClosure(function($cd) use($arg, $clazz) { return new $clazz($arg);} );
        $assembler = new ConstructClosureAssembler($componentDef);
        try {
            $component = $assembler->assemble();
            $this->fail();
        } catch(\seasar\container\exception\ClassUnmatchRuntimeException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function testUnmatchNull() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_ConstructClosureAssemblerTest');
        $arg = 'abc';
        $clazz = __NAMESPACE__ . '\B_ConstructClosureAssemblerTest';
        $componentDef->setConstructClosure(function($cd) use($arg, $clazz) {} );
        $assembler = new ConstructClosureAssembler($componentDef);
        try {
            $component = $assembler->assemble();
            $this->fail();
        } catch(\seasar\container\exception\ClassUnmatchRuntimeException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function testUnmatchNotObject() {
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_ConstructClosureAssemblerTest');
        $arg = 'abc';
        $clazz = __NAMESPACE__ . '\B_ConstructClosureAssemblerTest';
        $componentDef->setConstructClosure(function($cd) use($arg, $clazz) {return 1;} );
        $assembler = new ConstructClosureAssembler($componentDef);
        try {
            $component = $assembler->assemble();
            $this->fail();
        } catch(\seasar\container\exception\ClassUnmatchRuntimeException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_ConstructClosureAssemblerTest {}

class B_ConstructClosureAssemblerTest {
    public $a = null;
    public function __construct($a) {
        $this->a = $a;
    }
}
