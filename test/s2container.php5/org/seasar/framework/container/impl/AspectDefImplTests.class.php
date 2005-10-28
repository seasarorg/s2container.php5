<?php
class AspectDefImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetAspect() {
       
        print __METHOD__ . "\n";
       
        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('A'),
                                new S2Container_TraceInterceptor());
        $aspect = $ad->getValue();
        $this->assertIsA($aspect,'S2Container_TraceInterceptor');

        $aspect = $ad->getAspect();
        $this->assertIsA($aspect,'S2Container_AspectImpl');

        print "\n";
    } 
}
?>