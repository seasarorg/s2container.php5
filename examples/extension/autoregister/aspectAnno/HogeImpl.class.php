<?php
/**
 * @S2Container_AspectAnnotation (interceptor = new S2Container_TraceInterceptor() ,
 *                                pointcut = m1)
 */
class HogeImpl {
    const ASPECT = "interceptor = new S2Container_TraceInterceptor() ,
                    pointcut = m1 m2 ";

    public function m1($a,$b){
        return $a + $b;
    }

    /**
     * @S2Container_AspectAnnotation(interceptor = 
     *                               new S2Container_TraceInterceptor());
     */
    public function m2($a,$b){
        return $a * $b;
    }
}
?>
