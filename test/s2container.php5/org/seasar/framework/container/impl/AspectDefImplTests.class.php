<?php
class AspectDefImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetAspect() {
       
        print __METHOD__ . "\n";
       
        $ad = new AspectDefImpl(new PointcutImpl('A'),
                                new TraceInterceptor());
        $aspect = $ad->getValue();
        $this->assertIsA($aspect,'TraceInterceptor');

        $aspect = $ad->getAspect();
        $this->assertIsA($aspect,'AspectImpl');

        print "\n";
    } 
}
?>