<?php

class C implements IC{

    function C() {
    }
    
    /**
     * @S2Container_AspectAnnotation(new S2Container_TraceInterceptor)
     */
    function calc($a,$b){
        return $a + $b;
    }
}
?>