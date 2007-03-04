<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
class S2Container_AbstractConstructorAssemblerTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testAssembleDefault() {
        $asm = new Test_S2Container_AbstractConstructorAssembler(
            new S2Container_ComponentDefImpl('A_S2Container_AbstractConstructorAssembler'));       
        $this->assertType('Test_S2Container_AbstractConstructorAssembler',$asm);

        $a = $asm->assembleDefaultTest();
        $this->assertTrue($a instanceof A_S2Container_AbstractConstructorAssembler);
    }

    function testAssembleDefaultWithAspect() {
        $cd = new S2Container_ComponentDefImpl('A_S2Container_AbstractConstructorAssembler');
        $ad = new S2Container_AspectDefImpl(
                  new S2Container_PointcutImpl('A_S2Container_AbstractConstructorAssembler'),
                  new S2Container_TraceInterceptor());
        $cd->addAspectDef($ad);
        $asm = new Test_S2Container_AbstractConstructorAssembler($cd);

        $a = $asm->assembleDefaultTest();
        $this->assertTrue($a instanceof A_S2Container_AbstractConstructorAssemblerEnhancedByS2AOP);
    }

}

class Test_S2Container_AbstractConstructorAssembler extends S2Container_AbstractConstructorAssembler{
    
    public function __construct(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }   
    
    public function assembleDefaultTest() {
        return $this->assembleDefault();
    }

    public function assemble() {
    }
}

interface IA_S2Container_AbstractConstructorAssembler {}
class A_S2Container_AbstractConstructorAssembler 
    implements IA_S2Container_AbstractConstructorAssembler {}
?>
