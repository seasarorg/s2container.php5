<?php
/**
 * @S2Container_ComponentAnnotation(name = 'mockInterceptor')
 *      
 */
class MockInterceptor_S2Container_FileSystemComponentAutoRegister
    extends S2Container_AbstractInterceptor
{
    const COMPONENT = "name = mockInterceptor";
    
    public function invoke(S2Container_MethodInvocation $invocation){

        print __METHOD__ . " : mock interceptor called. \n";
        return;
    }
}
?>