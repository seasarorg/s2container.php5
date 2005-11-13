<?php
class AopProxyFactoryTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testAopProxyFactory() {
       
        print __METHOD__ . "\n";

        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('WextendAW'),
                                            new S2Container_TraceInterceptor());
       
        $c = S2Container_AopProxyFactory::create(new WextendAW(),
                                                 new ReflectionClass('WextendAW'),
                                                 array($ad->getAspect()),
                                                 array());
        if($c instanceof IW){
            $this->assertTrue(true);
            $c->awm1();
        }else{
            $this->assertTrue(false);
        }              
              
        $c = S2Container_AopProxyFactory::create(null,
                                                 new ReflectionClass('IW'),
                                                 array($ad->getAspect()),
                                                 array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
          
        $c = S2Container_AopProxyFactory::create(new WextendAW(),
                                                 null,
                                                 array($ad->getAspect()),
                                                 array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
        print "\n";
    }

    function testNullTarget() {
        print __METHOD__ . "\n";

        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('WextendAW'),
                                            new S2Container_TraceInterceptor());

        try{
            $c = S2Container_AopProxyFactory::create(null,
                                                     null,
                                                     array($ad->getAspect()),
                                                     array());
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_S2RuntimeException');
            print $e->getMessage() . "\n";
        }

    
        print "\n";
    }
    
    function testNullAspect() {
       
        print __METHOD__ . "\n";

        try{
            $c = S2Container_AopProxyFactory::create(new WextendAW(),
                                                     null,
                                                     array(),
                                                     array());
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_EmptyRuntimeException');
            print $e->getMessage() . "\n";
        }

        print "\n";
    }
}
?>
