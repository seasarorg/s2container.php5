<?php
/**
 * @S2Container_ComponentAnnotation(name = 'testC')
 * @S2Container_AspectAnnotation(interceptor = new S2Container_TraceInterceptor(),
 *                               pointcut = testTrace)
 *      
 */
class C_FileSystemComponentAutoRegisterTests {

    const COMPONENT = "name = testC";
    const ASPECT = "interceptor = new S2Container_TraceInterceptor(),
                    pointcut = testTrace";
    private $a;
    private $b;
    
    function __construct() {
    }

    /**
     * @S2Container_BindingAnnotation('a_FileSystemComponentAutoRegisterTests')
     */
    function setA(A_FileSystemComponentAutoRegisterTests $a){
        $this->a = $a;
    }
    const a_BINDING = "a_FileSystemComponentAutoRegisterTests";
    function getA(){
        return $this->a;
    }
    
    /**
     * @S2Container_BindingAnnotation('testB')
     */
    function setB(B_FileSystemComponentAutoRegisterTests $b){
        $this->b = $b;
    }
    const b_BINDING = "testB";
    function getB(){
        return $this->b;
    }
        
    /**
     * @S2Container_AspectAnnotation(Interceptor_FileSystemComponentAutoRegisterTests)
     */
    function testInterceptor(){
        return true;
    }

    /**
     * @S2Container_InitMethodAnnotation
     */
    function testInitMethod(){
        print __METHOD__ . " : init method called. \n";
    }
    const INIT_METHOD = "testInitMethod";
    
    /**
     * @S2Container_AspectAnnotation( new S2Container_TraceInterceptor())
     */
    function testTrace(){
        print __METHOD__ . " : trace called. \n";
    }
}
?>