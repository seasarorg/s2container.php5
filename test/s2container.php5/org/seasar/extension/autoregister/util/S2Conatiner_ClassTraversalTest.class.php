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
 * @package org.seasar.framework.extension.autoregister.util
 * @author klove
 */
class S2Conatiner_ClassTraversalTest
    extends PHPUnit2_Framework_TestCase {

    private static $SAMPLE_DIR;
                               
    public function __construct($name) {
        parent::__construct($name);
        self::$SAMPLE_DIR = dirname(__FILE__)
                          . "/sample/" 
                          . __CLASS__
                          . "/";
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }


    function testForEachTime() {
        $handler = new Handler_S2Container_ClassTraversal();
        S2Container_ClassTraversal::forEachTime(dirname(dirname(__FILE__)),
                                                $handler);
        $classMap = $handler->getClassMap();
        $this->assertTrue(isset($classMap[get_class($this)]));
    }

    function testNotDirectory() {
        $handler = new Handler_S2Container_ClassTraversal();
        S2Container_ClassTraversal::forEachTime('12345',
                                                $handler);
        $classMap = $handler->getClassMap();
        $this->assertTrue(count($classMap) == 0);
    }
}

class Handler_S2Container_ClassTraversal 
    implements S2Container_ClassTraversalClassHandler {

    private $classMap = array();
    
    public function getClassMap(){
        return $this->classMap;    
    }
    
    public function processClass($classFilePath, $shortClassName){
        $this->classMap[$shortClassName] = $classFilePath;
    }
}
?>
