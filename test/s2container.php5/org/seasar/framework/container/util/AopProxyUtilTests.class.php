<?php
class AopProxyUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetEnhancedClass() {
       
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('A','a');
        $aspect1 = new AspectDefImpl(new PointcutImpl('A'),new TraceInterceptor());
        $cd->addAspectDef($aspect1);
        
        $a = AopProxyUtil::getEnhancedClass($cd,array());
        $this->assertIsA($a,'UuCallAopProxyAEnhancedByS2AOP');

        print "\n";
    } 
}
?>