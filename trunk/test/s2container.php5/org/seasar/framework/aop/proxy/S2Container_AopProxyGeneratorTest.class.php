<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * @package org.seasar.framework.aop.proxy
 * @author klove
 */
class S2Container_AopProxyGeneratorTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testA() {
        print __METHOD__ . "\n";

        $ref = new ReflectionClass('A_S2Container_AopProxyGeneratorTests');
        $src = S2Container_ClassUtil::getSource($ref);

        $method = $ref->getMethod('aa_bb1');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb1(){ return $this->__call(\'aa_bb1\',array()); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb2');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb2(){ return $this->__call(\'aa_bb2\',array()); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb3');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb3(){ return $this->__call(\'aa_bb3\',array()); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb4');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb4(IA $a, IB $b){ return $this->__call(\'aa_bb4\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb5');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb5( IA $a, IB &$b){ return $this->__call(\'aa_bb5\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb6');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb6( IA $a,  IB $b){ return $this->__call(\'aa_bb6\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb7');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb7( IA $a,  IB $b ){ return $this->__call(\'aa_bb7\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb8');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb8($a = \'$b\',$b = "test"){ return $this->__call(\'aa_bb8\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb9');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb9($a = \' $b\',$b = "test"){ return $this->__call(\'aa_bb9\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb10');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb10(&$a = \'$b\',$b= "test"){ return $this->__call(\'aa_bb10\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb11');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb11($a="abc",$b = "te\"st"){ return $this->__call(\'aa_bb11\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb12');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb12($a="abc",$b = array()){ return $this->__call(\'aa_bb12\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb13');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb13($a="abc",$b = array(1, 2, 3)){ return $this->__call(\'aa_bb13\',array($a,$b)); }';
        $this->assertEquals($methodSrc,$result);

        $method = $ref->getMethod('aa_bb14');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb14(S2Container $a){ return $this->__call(\'aa_bb14\',array($a)); }';
        $this->assertEquals($methodSrc,$result);

/*
        $methods = $ref->getMethods();
        foreach($methods as $method){
            $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
            print "$methodSrc---\n";
        }
*/

        print "\n";
    }

    function testB() {
        print __METHOD__ . "\n";

        $ref = new ReflectionClass('B_S2Container_AopProxyGeneratorTests');
        $src = S2Container_ClassUtil::getSource($ref);

        $method = $ref->getMethod('hoge');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function hoge(){ return $this->__call(\'hoge\',array()); }';
        $this->assertEquals($methodSrc,$result);
        
        print "\n";
    }    

    function testC() {
        print __METHOD__ . "\n";

        $ref = new ReflectionClass('C_S2Container_AopProxyGeneratorTests');
        $src = S2Container_ClassUtil::getSource($ref);

        $method = $ref->getMethod('foo');
        try{
            $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }

        $method = $ref->getMethod('bar');
        try{
            $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }
        
        print "\n";
    }    
}

interface A_S2Container_AopProxyGeneratorTests {
    
    function aa_bb1();
    function aa_bb2();

    public function aa_bb3();
    
    public function aa_bb4(IA $a,
                           IB $b);

    public function aa_bb5(
                           IA $a,
                           IB &$b);

    public function aa_bb6( IA $a,

                           IB $b);

    public function aa_bb7( IA $a,

                           IB $b
                           );

    public function aa_bb8($a = '$b',$b = "test");

    public function aa_bb9($a = '
                                 $b',$b 
                                      = "test");

    public function aa_bb10(&$a = '$b',$b= "test");

    public function aa_bb11($a="abc",$b = "te\"st");
    public function aa_bb12($a="abc",$b = array());
    public function aa_bb13($a="abc",$b = array(1,
                                                2,
                                                3));
    public function aa_bb14(S2Container $a);
}

interface B_S2Container_AopProxyGeneratorTests { 
    public function hoge();
}

interface C_S2Container_AopProxyGeneratorTests {
    public function foo(); public function bar();
}
?>
