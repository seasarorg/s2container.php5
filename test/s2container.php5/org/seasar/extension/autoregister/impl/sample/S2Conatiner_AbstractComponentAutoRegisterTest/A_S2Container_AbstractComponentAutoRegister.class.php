<?php
/**
 * @S2Container_ComponentAnnotation(name = 'a')
 * @S2Container_AspectAnnotation(interceptor = new S2Container_TraceInterceptor(),
 *                               pointcut = testTrace)
 *      
 */
class A_S2Container_AbstractComponentAutoRegister {
    const COMPONENT = "name = a";
    const ASPECT = "interceptor = new S2Container_TraceInterceptor(),
                    pointcut = testTrace";
    const INIT_METHOD = "init1,init2";
                      
    function __construct() {
    }
    
    /**
     * @S2Container_InitMethodAnnotation
     */
    function init1(){
        print __METHOD__ . " called.\n";   
    }

    /**
     * @S2Container_InitMethodAnnotation
     */
    function init2(){
        print __METHOD__ . " called.\n";   
    }

    function testTrace($a,$b){
        print __METHOD__ . " called.\n";   
        return $a + $b;
    }

    /**
     * @S2Container_BindingAnnotation(1000)
     */
    function setData($data){
        $this->data = $data;       
    }
    const data_BINDING = 1000;
    
    function getData(){
        return $this->data;   
    }
}
?>