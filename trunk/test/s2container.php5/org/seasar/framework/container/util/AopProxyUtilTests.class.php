<?php
class AopProxyUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetProxyObject() {
       
        print __METHOD__ . "\n";

        $cd = new S2Container_ComponentDefImpl('A','a');
        $aspect1 = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('A'),new S2Container_TraceInterceptor());
        $cd->addAspectDef($aspect1);
        
        $a = S2Container_AopProxyUtil::getProxyObject($cd,array());
        $this->assertIsA($a,'AEnhancedByS2AOP');

        print "\n";
    } 
}
?>