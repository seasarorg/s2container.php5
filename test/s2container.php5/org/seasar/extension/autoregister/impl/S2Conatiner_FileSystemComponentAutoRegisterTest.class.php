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
 * @package org.seasar.framework.extension.autoregister.impl
 * @author klove
 */
class S2Conatiner_FileSystemComponentAutoRegisterTest
    extends PHPUnit2_Framework_TestCase {

    private static $SAMPLE_DIR;
    private static $SAMPLE2_DIR;
                               
    public function __construct($name) {
        parent::__construct($name);
        self::$SAMPLE_DIR = dirname(__FILE__)
                          . "/sample/" 
                          . __CLASS__
                          . "/";
        self::$SAMPLE2_DIR = dirname(__FILE__)
                           . "/sample2/" 
                           . __CLASS__
                           . "/";
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testInstantiate() {
        $register = new S2Container_FileSystemComponentAutoRegister();
        $this->assertType('S2Container_FileSystemComponentAutoRegister',$register);
    }
    
    function testRegisterAll() {
        $register = new S2Container_FileSystemComponentAutoRegister();
//        $register->setAutoNaming(new S2Container_DefaultAutoNaming());
        $container = new S2ContainerImpl();
        $register->setContainer($container);
        $register->addClassPattern(self::$SAMPLE_DIR);
        $register->registerAll();
        
        $this->assertTrue($container->hasComponentDef('a_S2Container_FileSystemComponentAutoRegister'));
        $this->assertTrue($container->hasComponentDef('A_S2Container_FileSystemComponentAutoRegister'));
        $this->assertTrue($container->hasComponentDef('testB'));
        $this->assertTrue($container->hasComponentDef('testC'));
        $this->assertTrue($container->hasComponentDef('interceptor'));
        $c = $container->getComponent('testC');

        $this->assertType('A_S2Container_FileSystemComponentAutoRegister',$c->getA());
        $this->assertType('B_S2Container_FileSystemComponentAutoRegister',$c->getB());
        $c->testInterceptor();
        $c->testTrace();
    }    

    function testRegisterInterceptor() {
        $register = new S2Container_FileSystemComponentAutoRegister();
        $container = new S2ContainerImpl();
        $register->setContainer($container);
        $register->addClassPattern(self::$SAMPLE_DIR);
        $register->registerAll();

        $this->assertTrue($container->hasComponentDef('testID'));
        $this->assertTrue($container->hasComponentDef('mockInterceptor'));
        $d = $container->getComponent('testID');
        $d->testMock();
    }    

    function testContainer() {
        $register = new S2Container_FileSystemComponentAutoRegister();
        $container = new S2ContainerImpl();
        $register->setContainer($container);
        $con = $register->getContainer();
        $this->assertTrue($con === $container);
    }    

    function testClassPatternSize() {
        $register = new S2Container_FileSystemComponentAutoRegister();
        $this->assertEquals($register->getClassPatternSize(),0);
        $register->addClassPattern(dirname(__FILE__));
        $this->assertEquals($register->getClassPatternSize(),1);
    }    

    function testDirException() {
        $register = new S2Container_FileSystemComponentAutoRegister();

        try{
            $register->addClassPattern('dddd');
            $this->assertTrue(false);
        }catch(Exception $e){
            $this->assertTrue(true);
            print "{$e->getMessage()}\n";
        }
    }    

    function testIsIgnore() {
        $register = new Test_S2Container_FileSystemComponentAutoRegister();
        $register->addIgnoreClassPattern("Foo,Bar");
        $this->assertEquals($register->isIgnore('Foo'),true);
        $this->assertEquals($register->isIgnore('Bar'),true);
        $this->assertEquals($register->isIgnore('Hoge'),false);
    }

    function testRegisterTwiseException() {
        $register = new S2Container_FileSystemComponentAutoRegister();
        $container = new S2ContainerImpl();
        $register->setContainer($container);
        $register->addClassPattern(self::$SAMPLE_DIR, 'A_');
        $register->addClassPattern(self::$SAMPLE_DIR, 'A_');
        $register->registerAll();

        try {
            $d = $container->getComponent('A_S2Container_FileSystemComponentAutoRegister');
            $this->fail();
        } catch(Exception $e) {
        	$this->assertTrue(true);
            print "{$e->getMessage()} \n";
        }
    }

    function testTwoClassPattern() {
        $register = new S2Container_FileSystemComponentAutoRegister();
        $container = new S2ContainerImpl();
        $register->setContainer($container);
        $register->addClassPattern(self::$SAMPLE_DIR, 'A_');
        $register->addClassPattern(self::$SAMPLE2_DIR);
        $register->registerAll();

        $this->assertEquals(1 , $container->getComponentDefSize());
    }

    function testProcessClass() {
        $register = new S2Container_FileSystemComponentAutoRegister();
        $register->setContainer(new S2ContainerImpl());
        $classFilePath = self::$SAMPLE_DIR
                       . 'D_S2Container_FileSystemComponentAutoRegister.class.php';
        $cp = new S2Container_ClassPattern();
        $cp->setShortClassNames("^D_");
        $register->setProcessingClassPattern($cp);
        
        $register->processClass($classFilePath,
                            'D_S2Container_FileSystemComponentAutoRegister');
        $a = $register->getContainer()->getComponent('a');
        $this->assertType('S2Container_DefaultAopProxy',$a);
        $cd = $register->getContainer()->findComponentDefs('a');
        $this->assertEquals(count($cd),1);
        
        $classFilePath = self::$SAMPLE_DIR
                       . 'E_S2Container_FileSystemComponentAutoRegister.class.php';
        $register->processClass($classFilePath,'e');
        try {
            $a = $register->getContainer()->getComponent('e');
            $this->fail();
        } catch(Exception $e) {
        	print "{$e->getMessage()} \n";
        }
    }

    function testProcessClassIgnore() {
        $register = new S2Container_FileSystemComponentAutoRegister();
        $register->setContainer(new S2ContainerImpl());
        $classFilePath = self::$SAMPLE_DIR
                       . 'D_S2Container_FileSystemComponentAutoRegister.class.php';

        $cp = new S2Container_ClassPattern();
        $cp->setShortClassNames("^D_");
        $register->addIgnoreClassPattern($cp);
        $register->setProcessingClassPattern($cp);
        
        $register->processClass($classFilePath,
                            'D_S2Container_FileSystemComponentAutoRegister');
        $cd = $register->getContainer()->findComponentDefs('a');
        $this->assertEquals(count($cd),0);
    }
}

class Test_S2Container_FileSystemComponentAutoRegister
    extends S2Container_FileSystemComponentAutoRegister{

    public function isIgnore($name){
        return parent::isIgnore($name);    
    }
}
?>
