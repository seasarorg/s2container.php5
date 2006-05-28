<?php
class TraceInterceptorTests2 extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testTraceInterceptor() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new Date(),'Date', array($aspect));
        $this->assertEqual($proxy->getTime(),'12:00:30');
        print "\n";
    }

    function testArgs() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("culc2"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new I(),'I', array($aspect));
        $proxy->culc2(4,8);
        $this->assertEqual($proxy->getResult(),12);
        print "\n";
    }
}
?>