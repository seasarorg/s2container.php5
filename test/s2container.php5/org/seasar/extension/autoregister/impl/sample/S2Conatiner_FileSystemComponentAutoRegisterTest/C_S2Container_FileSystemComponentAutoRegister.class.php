<?php
/**
 * @S2Container_ComponentAnnotation(name = 'testC')
 * @S2Container_AspectAnnotation(interceptor = new S2Container_TraceInterceptor(),
 *                               pointcut = testTrace)
 *      
 */
class C_S2Container_FileSystemComponentAutoRegister {

    const COMPONENT = "name = testC";
    const ASPECT = "interceptor = new S2Container_TraceInterceptor(),
                    pointcut = testTrace";
    private $a;
    private $b;
    
    function __construct() {
    }

    /**
     * @S2Container_BindingAnnotation('a_S2Container_FileSystemComponentAutoRegister')
     */
    function setA(A_S2Container_FileSystemComponentAutoRegister $a){
        $this->a = $a;
    }
    const a_BINDING = "a_S2Container_FileSystemComponentAutoRegister";
    function getA(){
        return $this->a;
    }
    
    /**
     * @S2Container_BindingAnnotation('testB')
     */
    function setB(B_S2Container_FileSystemComponentAutoRegister $b){
        $this->b = $b;
    }
    const b_BINDING = "testB";
    function getB(){
        return $this->b;
    }
        
    /**
     * @S2Container_AspectAnnotation(Interceptor_S2Container_FileSystemComponentAutoRegister)
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