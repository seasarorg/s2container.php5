<?php
class InterceptorsTests2 extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testTraceThrowsInterceptor() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("throwE"));
       
        $tt = new S2Container_TraceThrowsInterceptor();
        $aspect = new S2Container_AspectImpl($tt, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new Q(),'Q', array($aspect));
        try{
            $proxy->throwE();
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_UnsupportedOperationException');
        }
        print "\n";
    }
   
    function testInterceptorChain() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
        $chain = new S2Container_InterceptorChain();
        $chain->add(new S2Container_TraceInterceptor());
        $chain->add(new S2Container_MockInterceptor('pm1','mock value.'));
        $aspect = new S2Container_AspectImpl($chain, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new P(),'P', array($aspect));
        $this->assertEqual($proxy->pm1(),'mock value.');

        print "\n";
    }
      
    function testPrototypeDelegateInterceptor() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('DelegateB','b');
          
        $cd = $container->getComponentDef('b');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_PROTOTYPE);
          
        $pointcut = new S2Container_PointcutImpl(array("ma"));
        $proto = new S2Container_PrototypeDelegateInterceptor($container);
        $proto->setTargetName('b');
        $proto->addMethodNameMap('ma','mb');
        $aspect = new S2Container_AspectImpl($proto, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new DelegateA(),'DelegateA', array($aspect));

        $this->assertEqual($proxy->ma(),'mb called.');

        print "\n";
    }
   
    function testTraceAndMock() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
       
        $taspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $mock = new S2Container_MockInterceptor('pm1','mock value.');
        $aspect = new S2Container_AspectImpl($mock, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new P(),'P', array($taspect,$aspect));
        $this->assertEqual($proxy->pm1(),'mock value.');

        print "\n";
    }
   
    function testMockInterceptor() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
       
        $mock = new S2Container_MockInterceptor('pm1','mock value.');
        $aspect = new S2Container_AspectImpl($mock, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,'IP', array($aspect));
        $this->assertEqual($proxy->pm1(),'mock value.');

        print "\n";
    }



    function testTraceInterceptorContaienr() {
       
        print __METHOD__ . "\n";

        $container = new S2ContainerImpl();
        $container->register('Date','d');
        $cd = $container->getComponentDef('d');

        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $aspectDef = new S2Container_AspectDefImpl(new S2Container_TraceInterceptor(), $pointcut);
        $cd->addAspectDef($aspectDef);
        $d = $container->getComponent('d');

        $this->assertEqual($d->getTime(),'12:00:30');

        print "\n";
    }

    function testUuCallMethod() {
       
        print __METHOD__ . "\n";
       
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(),new S2Container_PointcutImpl(array("getMessage")));
        $proxy = S2Container_AopProxyFactory::create(new X(),'X', array($aspect));
        $this->assertEqual($proxy->getMessage(),'hello');
        print "\n";
    }

    function testNoMethodInterface() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl('IA');
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,'IA', array($aspect));
        $this->assertNotNull($proxy);
        print "\n";
    } 

    function testFinalClassAspect() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl('Y');
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        try{
            $proxy = S2Container_AopProxyFactory::create(null,'Y', array($aspect));
        }catch(Exception $e){

            // no exception occure. final class permitted.
            if($e instanceof S2Container_S2RuntimeException ){
            	$this->assertTrue(true);
            	print($e->getMessage()."\n");
            }else{
            	$this->assertTrue(false);
            }        	
        }

        print "\n";
    } 

    function testStaticMethodAspect() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array('z1','z2'));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new Z(),'Z', array($aspect));
       	$this->assertNotNull($proxy);
        $this->assertTrue($proxy->z2());

        print "\n";
    } 

    function testInvokeInterfaceMethod() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new S2Container_PointcutImpl(array('om2'));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,'IO', array($aspect));
       	$this->assertNotNull($proxy);
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