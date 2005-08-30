<?php
class DelegateInterceptorTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testNoTarget() {

        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("ma"));
       
        $delegate = new DelegateInterceptor();
        $delegate->addMethodNameMap('ma','mb');
        $aspect = new AspectImpl($delegate, $pointcut);
        $aopProxy = new AopProxy('DelegateA', array($aspect));
        $proxy = $aopProxy->create();
        try{
            $this->assertEqual($proxy->ma(),'mb called.');
        }catch(Exception $e){
        	$this->assertIsA($e,'EmptyRuntimeException');
        }
        print "\n";
    }

    function testChangeMethodName() {

        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("ma"));
       
        $delegate = new DelegateInterceptor(new DelegateB());
        $delegate->addMethodNameMap('ma','mb');
        $aspect = new AspectImpl($delegate, $pointcut);
        $aopProxy = new AopProxy('DelegateA', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEqual($proxy->ma(),'mb called.');

        print "\n";
    }

    function testDelegate() {

        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("mc"));
       
        $delegate = new DelegateInterceptor(new DelegateB());
        $aspect = new AspectImpl($delegate, $pointcut);
        $aopProxy = new AopProxy('DelegateA', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEqual($proxy->mc(),'Delegate B mc called.');

        print "\n";
    }
   
}
?>