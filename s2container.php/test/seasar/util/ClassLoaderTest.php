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
 * @package   seasar.util
 * @author    klove
 */
namespace seasar\util;
class ClassLoaderTest extends \PHPUnit_Framework_TestCase {

    public function testImport() {
        $classesTmp = ClassLoader::$CLASSES;
        ClassLoader::$CLASSES = array();
        ClassLoader::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoaderTest');
        $cPath = dirname(__FILE__) . DIRECTORY_SEPARATOR
               . 'ClassLoaderTest' . DIRECTORY_SEPARATOR
               . 'a' . DIRECTORY_SEPARATOR
               . 'b' . DIRECTORY_SEPARATOR . 'C.php';
        $this->assertEquals(ClassLoader::$CLASSES, array('a\b\C' => $cPath));
        ClassLoader::$CLASSES = $classesTmp;
    }

    public function testImportFail() {
        try {
            ClassLoader::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoaderTest' . DIRECTORY_SEPARATOR . 'XXX');
            $this->fail();
        } catch (\seasar\exception\FileNotFoundException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function testImportStrict() {
        $classesTmp = ClassLoader::$CLASSES;
        ClassLoader::$CLASSES = array();
        ClassLoader::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoaderTest', array(), true);
        $cPath = dirname(__FILE__) . DIRECTORY_SEPARATOR
               . 'ClassLoaderTest' . DIRECTORY_SEPARATOR
               . 'a' . DIRECTORY_SEPARATOR
               . 'b' . DIRECTORY_SEPARATOR . 'C.php';
        $this->assertEquals(ClassLoader::$CLASSES, array('C' => $cPath));

        ClassLoader::$CLASSES = array();
        ClassLoader::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoaderTest', array('hoge'), true);
        $cPath = dirname(__FILE__) . DIRECTORY_SEPARATOR
               . 'ClassLoaderTest' . DIRECTORY_SEPARATOR
               . 'a' . DIRECTORY_SEPARATOR
               . 'b' . DIRECTORY_SEPARATOR . 'C.php';
        $this->assertEquals(ClassLoader::$CLASSES, array('hoge\C' => $cPath));

        ClassLoader::$CLASSES = $classesTmp;
    }

    public function testImportPear() {
        $classesTmp = ClassLoader::$CLASSES;
        ClassLoader::$CLASSES = array();
        ClassLoader::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoaderTest', array(), false, true);
        $cPath = dirname(__FILE__) . DIRECTORY_SEPARATOR
               . 'ClassLoaderTest' . DIRECTORY_SEPARATOR
               . 'a' . DIRECTORY_SEPARATOR
               . 'b' . DIRECTORY_SEPARATOR . 'C.php';
        $this->assertEquals(ClassLoader::$CLASSES, array('a_b_C' => $cPath));

        ClassLoader::$CLASSES = array();
        ClassLoader::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoaderTest', array(), true, true);
        $cPath = dirname(__FILE__) . DIRECTORY_SEPARATOR
               . 'ClassLoaderTest' . DIRECTORY_SEPARATOR
               . 'a' . DIRECTORY_SEPARATOR
               . 'b' . DIRECTORY_SEPARATOR . 'C.php';
        $this->assertEquals(ClassLoader::$CLASSES, array('C' => $cPath));

        ClassLoader::$CLASSES = array();
        ClassLoader::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoaderTest', array('hoge'), true, true);
        $cPath = dirname(__FILE__) . DIRECTORY_SEPARATOR
               . 'ClassLoaderTest' . DIRECTORY_SEPARATOR
               . 'a' . DIRECTORY_SEPARATOR
               . 'b' . DIRECTORY_SEPARATOR . 'C.php';
        $this->assertEquals(ClassLoader::$CLASSES, array('hoge_C' => $cPath));

        ClassLoader::$CLASSES = $classesTmp;
    }

    public function testRecursive() {
        $classesTmp = ClassLoader::$CLASSES;
        ClassLoader::$CLASSES = array();
        ClassLoader::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoaderTest', array(), true, false, false);
        $cPath = dirname(__FILE__) . DIRECTORY_SEPARATOR
               . 'ClassLoaderTest' . DIRECTORY_SEPARATOR
               . 'a' . DIRECTORY_SEPARATOR
               . 'b' . DIRECTORY_SEPARATOR . 'C.php';
        $this->assertEquals(count(ClassLoader::$CLASSES), 0);

        ClassLoader::$CLASSES = $classesTmp;
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}


