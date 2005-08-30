<?php
class InterceptorsTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testTraceThrowsInterceptor() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("throwE"));
       
        $tt = new TraceThrowsInterceptor();
        $aspect = new AspectImpl($tt, $pointcut);
        $aopProxy = new AopProxy('Q', array($aspect));
        $proxy = $aopProxy->create();
        try{
            $proxy->throwE();
        }catch(Exception $e){
            $this->assertIsA($e,'UnsupportedOperationException');
        }
        print "\n";
    }
   
    function testInterceptorChain() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("pm1"));
        $chain = new InterceptorChain();
        $chain->add(new TraceInterceptor());
        $chain->add(new MockInterceptor('pm1','mock value.'));
        $aspect = new AspectImpl($chain, $pointcut);
        $aopProxy = new AopProxy('P', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEqual($proxy->pm1(),'mock value.');

        print "\n";
    }
      
    function testPrototypeDelegateInterceptor() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('DelegateB','b');
          
        $cd = $container->getComponentDef('b');
        $cd->setInstanceMode(ContainerConstants::INSTANCE_PROTOTYPE);
          
        $pointcut = new PointcutImpl(array("ma"));
        $proto = new PrototypeDelegateInterceptor($container);
        $proto->setTargetName('b');
        $proto->addMethodNameMap('ma','mb');
        $aspect = new AspectImpl($proto, $pointcut);
        $aopProxy = new AopProxy('DelegateA', array($aspect));
        $proxy = $aopProxy->create();

        $this->assertEqual($proxy->ma(),'mb called.');

        print "\n";
    }
   
    function testTraceAndMock() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("pm1"));
       
        $taspect = new AspectImpl(new TraceInterceptor(), $pointcut);
        $mock = new MockInterceptor('pm1','mock value.');
        $aspect = new AspectImpl($mock, $pointcut);
        $aopProxy = new AopProxy('P', array($taspect,$aspect));
        $proxy = $aopProxy->create();
        $this->assertEqual($proxy->pm1(),'mock value.');

        print "\n";
    }
   
    function testMockInterceptor() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("pm1"));
       
        $mock = new MockInterceptor('pm1','mock value.');
        $aspect = new AspectImpl($mock, $pointcut);
        $aopProxy = new AopProxy('IP', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEqual($proxy->pm1(),'mock value.');

        print "\n";
    }



    function testTraceInterceptorContaienr() {
       
        print __METHOD__ . "\n";

        $container = new S2ContainerImpl();
        $container->register('Date','d');
        $cd = $container->getComponentDef('d');

        $pointcut = new PointcutImpl(array("getTime"));
        $aspectDef = new AspectDefImpl(new TraceInterceptor(), $pointcut);
        $cd->addAspectDef($aspectDef);
        $d = $container->getComponent('d');

        $this->assertEqual($d->getTime(),'12:00:30');

        print "\n";
    }

    function testUuCallMethod() {
       
        print __METHOD__ . "\n";
       
        $aspect = new AspectImpl(new TraceInterceptor(),new PointcutImpl(array("getMessage")));
        $aopProxy = new AopProxy('X', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEqual($proxy->getMessage(),'hello');
        print "\n";
    }

    function testNoMethodInterface() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl('IA');
        $aspect = new AspectImpl(new TraceInterceptor(), $pointcut);
        $aopProxy = new AopProxy('IA', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertNotNull($proxy);
        print "\n";
    } 

    function testFinalClassAspect() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl('Y');
        $aspect = new AspectImpl(new TraceInterceptor(), $pointcut);
        $aopProxy = new AopProxy('Y', array($aspect));
        try{
            $proxy = $aopProxy->create();
        }catch(Exception $e){
            if($e instanceof S2RuntimeException ){
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
       
        $pointcut = new PointcutImpl(array('z1','z2'));
        $aspect = new AspectImpl(new TraceInterceptor(), $pointcut);
        $aopProxy = new AopProxy('Z', array($aspect));
       	$this->assertNotNull($aopProxy);
       	$proxy = $aopProxy->create();
        $this->assertTrue($proxy->z2());

        // check generated src at UuCallAopProxyFactory : 101
        print "\n";
    } 

    function testInvokeInterfaceMethod() {
       
        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array('om2'));
        $aspect = new AspectImpl(new TraceInterceptor(), $pointcut);
        $aopProxy = new AopProxy('IO', array($aspect));
       	$this->assertNotNull($aopProxy);
       	$proxy = $aopProxy->create();
       	try{
       	    $proxy->om1();	
       	}catch(Exception $e){
       		$this->assertIsA($e,'S2RuntimeException');
       		print $e->getMessage() . "\n";
       	}

        print "\n";
    } 
}
?>