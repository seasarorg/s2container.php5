<?php
class AbstractInterceptorTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testCreateProxy() {

        print __METHOD__ . "\n";
       
        $interceptor = new TraceInterceptor();
        $proxy = $interceptor->createProxy(new ReflectionClass('I'));
        $proxy->culc();
        $this->assertEqual($proxy->getResult(),2);

        print "\n";
    }

    function testGetTargetClass() {

        print __METHOD__ . "\n";
       
        $interceptor = new TestInterceptor();
        $proxy = $interceptor->createProxy(new ReflectionClass('I'));
        $proxy->culc();
        $this->assertIsA($interceptor->getClazz(),'ReflectionClass');

        print "\n";
    }

    function testGetComponentDef() {

        print __METHOD__ . "\n";
       
        $pointcut = new PointcutImpl(array("getTime"));
        $interceptor = new TestInterceptor();
        $aspect = new AspectImpl($interceptor, $pointcut);
        $params[ContainerConstants::COMPONENT_DEF_NAME] = new ComponentDefImpl('Date','date');
        $aopProxy = new AopProxy('Date', array($aspect),$params);
        $proxy = $aopProxy->create();
        $proxy->getTime();
        $cd = $interceptor->getCd();
        
        $this->assertIsA($cd,'ComponentDefImpl');
        
        print "\n";
    }

}

class TestInterceptor extends AbstractInterceptor {
    private $clazz;
    private $cd;
    
    public function invoke(MethodInvocation $invocation){
	    $this->clazz = $this->getTargetClass($invocation);
	    $this->cd = $this->getComponentDef($invocation);
    }
    
    public function getClazz(){
        return $this->clazz;	
    }

    public function getCd(){
        return $this->cd;	
    }
}

?>