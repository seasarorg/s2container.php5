<?php
class DefaultAopProxyTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testNullTarget() {
        print __METHOD__ . "\n";

        $pointcut = new S2Container_PointcutImpl(array("test"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,'C', array($aspect));
        try{
            $proxy->say();
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_S2RuntimeException');
            print $e->getMessage() . "\n";
        }
  
        print "\n";
    }
}
?>
