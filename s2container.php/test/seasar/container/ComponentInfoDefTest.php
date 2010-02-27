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
 * @package   seasar.container
 * @author    klove
 */
namespace seasar\container;
class ComponentInfoDefTest extends \PHPUnit_Framework_TestCase {

    public function testS2Comp() {
        $info = s2comp('A')->
                setName('a')->
                setInstance('singleton')->
                setAutoBinding('auto')->
                setNamespace('sample')->
                setConstructClosure(function($cd){});

        $this->assertEquals($info->getClassName(), 'A');
        $this->assertEquals($info->getName(), 'a');
        $this->assertEquals($info->getInstance(), 'singleton');
        $this->assertEquals($info->getAutoBinding(), 'auto');
        $this->assertEquals($info->getNamespace(), 'sample');
        $this->assertTrue($info->getConstructClosure() instanceof \Closure);
    }

    public function testUsePhpNamespace() {
        $info = s2comp('seasar\container\A_ComponentInfoDefTest')->usePhpNamespace();
        $this->assertEquals($info->getNamespace(), 'seasar.container');

        $info = s2comp('\seasar\container\A_ComponentInfoDefTest')->usePhpNamespace();
        $this->assertEquals($info->getNamespace(), 'seasar.container');

        $info = s2comp('PDO')->usePhpNamespace();
        $this->assertEquals($info->getNamespace(), null);

        $info = s2comp('seasar\container\A_ComponentInfoDefTest')
                  ->setNamespace('x.y')
                  ->usePhpNamespace();
        $this->assertEquals($info->getNamespace(), 'x.y');
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_ComponentInfoDefTest {}
