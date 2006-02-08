<?php
class HandleThrowableInterceptor extends S2Container_ThrowsInterceptor {
	
	public function handleThrowable(Exception $t, S2Container_MethodInvocation $invocation){

		throw new S2Container_S2RuntimeException("ESSR0007", array("arg"));
	}
}
?>