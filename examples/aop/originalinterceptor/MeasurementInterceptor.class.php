<?php
class MeasurementInterceptor extends S2Container_AbstractInterceptor {
	public function invoke(S2Container_MethodInvocation $invocation){
		$start = 0;
		$end = 0;
		$buf = "";

		$buf = $this->getTargetClass($invocation)->getName();
		$buf .= "#";
		$buf .= $invocation->getMethod()->getName();
		$buf .= "(";
		$args = $invocation->getArguments();
		$buf .= implode($args) . ")";
		try {
			$start = $this->microtime_float();
			$ret = $invocation->proceed();
			$end = $this->microtime_float();
			$buf .= " : ";
        	$t = $end - $start;
	    	print $buf . $t . "\n";
			return $ret;
		} catch (Exception $t) {
			$buf .= " Exception:";
			$buf .= $t->getMessage();
			throw $t;
		}
	}
	
	private function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    } 
}
?>