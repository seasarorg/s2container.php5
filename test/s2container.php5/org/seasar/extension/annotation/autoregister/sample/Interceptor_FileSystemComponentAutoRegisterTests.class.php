<?php
/**
 * @S2Container_ComponentAnnotation(name => 'interceptor')
 *      
 */
class Interceptor_FileSystemComponentAutoRegisterTests 
    extends S2Container_AbstractInterceptor
{
    /**
     * @see S2Container_MethodInterceptor::invoke()
     */
    public function invoke(S2Container_MethodInvocation $invocation){

        print __METHOD__ . " : interceptor called. \n";
        return $invocation->proceed();
    }
}
?>