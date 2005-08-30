<?php
class TraceInterceptorTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testTraceInterceptor() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("getTime"));
        $aspect = new AspectImpl(new TraceInterceptor(), $pointcut);
        $aopProxy = new AopProxy('Date', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEqual($proxy->getTime(),'12:00:30');
        print "\n";
    }

    function testArgs() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("culc2"));
        $aspect = new AspectImpl(new TraceInterceptor(), $pointcut);
        $aopProxy = new AopProxy('I', array($aspect));
        $proxy = $aopProxy->create();
        $proxy->culc2(4,8);
        $this->assertEqual($proxy->getResult(),12);
        print "\n";
    }
}
?>