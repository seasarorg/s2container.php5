<?php
class MethodInvocationTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testNullTarget() {
        print __METHOD__ . "\n";

        $pointcut = new S2Container_PointcutImpl(array("om1"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,'IO', array($aspect));
        try{
            $proxy->om1();
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_S2RuntimeException');
            print $e->getMessage() . "\n";
        }
  
        print "\n";
    }
}
?>
