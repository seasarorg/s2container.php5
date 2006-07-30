<?php
/**
 * @S2Container_ComponentAnnotation(name = 'testID')
 * @S2Container_AspectAnnotation(interceptor = mockInterceptor)
 *      
 */
interface ID_S2Container_FileSystemComponentAutoRegister {

    const COMPONENT = "name = testID";
    const ASPECT = "interceptor = mockInterceptor";

    function testMock();
}
?>
