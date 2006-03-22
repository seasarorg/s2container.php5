<?php
class AopProxyGeneratorTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testA() {
        print __METHOD__ . "\n";

        $ref = new ReflectionClass('A_AopProxyGeneratorTests');
        $src = S2Container_ClassUtil::getSource($ref);
        $methods = $ref->getMethods();

        foreach($methods as $method){
            $methodSrc = S2Container_AopProxyGenerator::getMethodDefinition(
                                     $method,$src);
            print "$methodSrc---\n";
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
}
?>
