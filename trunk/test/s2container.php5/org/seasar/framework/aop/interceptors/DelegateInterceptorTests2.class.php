<?php
class DelegateInterceptorTests2 extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testNoTarget() {

        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("ma"));
       
        $delegate = new S2Container_DelegateInterceptor();
        $delegate->addMethodNameMap('ma','mb');
        $aspect = new S2Container_AspectImpl($delegate, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new DelegateA(),'DelegateA', array($aspect));
        try{
            $this->assertEqual($proxy->ma(),'mb called.');
        }catch(Exception $e){
        	$this->assertIsA($e,'S2Container_EmptyRuntimeException');
        }
        print "\n";
    }

    function testChangeMethodName() {

        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("ma"));
       
        $delegate = new S2Container_DelegateInterceptor(new DelegateB());
        $delegate->addMethodNameMap('ma','mb');
        $aspect = new S2Container_AspectImpl($delegate, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new DelegateA(),'DelegateA', array($aspect));
        $this->assertEqual($proxy->ma(),'mb called.');

        print "\n";
    }

    function testDelegate() {

        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("mc"));
       
        $delegate = new S2Container_DelegateInterceptor(new DelegateB());
        $aspect = new S2Container_AspectImpl($delegate, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new DelegateA(),'DelegateA', array($aspect));
        $this->assertEqual($proxy->mc(),'Delegate B mc called.');

        print "\n";
    }
   
}
?>