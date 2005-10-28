<?php
class AbstractInterceptorTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testCreateProxy() {

        print __METHOD__ . "\n";
       
        $interceptor = new S2Container_TraceInterceptor();
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
       
        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $interceptor = new TestInterceptor();
        $aspect = new S2Container_AspectImpl($interceptor, $pointcut);
        $params[S2Container_ContainerConstants::COMPONENT_DEF_NAME] = new S2Container_ComponentDefImpl('Date','date');
        $aopProxy = new S2Container_AopProxy('Date', array($aspect),$params);
        $proxy = $aopProxy->create();
        $proxy->getTime();
        $cd = $interceptor->getCd();
        
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');
        
        print "\n";
    }

}

class TestInterceptor extends S2Container_AbstractInterceptor {
    private $clazz;
    private $cd;
    
    public function invoke(S2Container_MethodInvocation $invocation){
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