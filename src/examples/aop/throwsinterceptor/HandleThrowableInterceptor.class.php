<?php
class HandleThrowableInterceptor extends ThrowsInterceptor {
	
	public function handleThrowable(Exception $t, MethodInvocation $invocation){

		throw new S2RuntimeException("ESSR0007", array("arg"));
	}
}
?>