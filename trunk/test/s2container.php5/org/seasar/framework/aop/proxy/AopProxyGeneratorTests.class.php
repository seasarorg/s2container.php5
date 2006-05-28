<?php
class AopProxyGeneratorTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testA() {
        print __METHOD__ . "\n";

        $ref = new ReflectionClass('A_AopProxyGeneratorTests');
        $src = S2Container_ClassUtil::getSource($ref);

        $method = $ref->getMethod('aa_bb1');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb1(){ return $this->__call(\'aa_bb1\',array()); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb2');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb2(){ return $this->__call(\'aa_bb2\',array()); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb3');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb3(){ return $this->__call(\'aa_bb3\',array()); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb4');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb4(IA $a, IB $b){ return $this->__call(\'aa_bb4\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb5');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb5( IA $a, IB &$b){ return $this->__call(\'aa_bb5\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb6');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb6( IA $a,  IB $b){ return $this->__call(\'aa_bb6\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb7');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb7( IA $a,  IB $b ){ return $this->__call(\'aa_bb7\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb8');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb8($a = \'$b\',$b = "test"){ return $this->__call(\'aa_bb8\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb9');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb9($a = \' $b\',$b = "test"){ return $this->__call(\'aa_bb9\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb10');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb10(&$a = \'$b\',$b= "test"){ return $this->__call(\'aa_bb10\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb11');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb11($a="abc",$b = "te\"st"){ return $this->__call(\'aa_bb11\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb12');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb12($a="abc",$b = array()){ return $this->__call(\'aa_bb12\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb13');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb13($a="abc",$b = array(1, 2, 3)){ return $this->__call(\'aa_bb13\',array($a,$b)); }';
        $this->assertEqual($methodSrc,$result);

        $method = $ref->getMethod('aa_bb14');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function aa_bb14(S2Container $a){ return $this->__call(\'aa_bb14\',array($a)); }';
        $this->assertEqual($methodSrc,$result);

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

        $ref = new ReflectionClass('B_AopProxyGeneratorTests');
        $src = S2Container_ClassUtil::getSource($ref);

        $method = $ref->getMethod('hoge');
        $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
        $result = 'public function hoge(){ return $this->__call(\'hoge\',array()); }';
        $this->assertEqual($methodSrc,$result);
        
        print "\n";
    }    

    function testC() {
        print __METHOD__ . "\n";

        $ref = new ReflectionClass('C_AopProxyGeneratorTests');
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

interface A_AopProxyGeneratorTests {
    
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
?>

<?php interface B_AopProxyGeneratorTests { public function hoge();}?>

<?php 
interface C_AopProxyGeneratorTests {
    public function foo(); public function bar();
}
?>
