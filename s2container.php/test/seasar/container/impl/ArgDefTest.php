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
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar\container\impl;
class ArgDefTest extends \PHPUnit_Framework_TestCase {

    public function testGetValue() {
        $arg = new ArgDef();
        $this->assertNotNull($arg);

        $arg->setValue('test val');
        $this->assertEquals($arg->getValue(),'test val');
        
        $arg->setExpression('100');
        $this->assertEquals($arg->getValue(),100);

        $arg = new ArgDef();
        $this->assertNotNull($arg);

        $cd = new ComponentDefImpl('\seasar\container\impl\A_ArgDef','a');
        $arg->setChildComponentDef($cd);
        $this->assertTrue($arg->getValue() instanceof \seasar\container\impl\A_ArgDef);
    } 

    public function testMetaDef(){
        $arg = new ArgDef();
        $this->assertNotNull($arg);
 
        $md1 = new MetaDef('a','A');
        $arg->addMetaDef($md1);
        $md2 = new MetaDef('b','B');
        $arg->addMetaDef($md2);
        $md3 = new MetaDef('c','C');
        $arg->addMetaDef($md3);

        $this->assertEquals($arg->getMetaDefSize(),3);

        $md = $arg->getMetaDef('a');
        $this->assertTrue($md === $md1);
    
        $md = $arg->getMetaDef(1);
        $this->assertTrue($md === $md2);
    }

    public function testMetaDefs(){
        $arg = new ArgDef();
        $this->assertNotNull($arg);
 
        $md1 = new MetaDef('a','A1');
        $arg->addMetaDef($md1);
        $md2 = new MetaDef('a','A2');
        $arg->addMetaDef($md2);
        $md3 = new MetaDef('a','A3');
        $arg->addMetaDef($md3);

        $this->assertEquals($arg->getMetaDefSize(),3);

        $mds = $arg->getMetaDefs('a');
        $this->assertEquals(count($mds),3);
        $this->assertTrue($mds[0] === $md1);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }

}

interface IA_ArgDef {}
class A_ArgDef implements IA_ArgDef {}
