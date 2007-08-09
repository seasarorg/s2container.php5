<?php
class TimeInterceptor extends S2Container_AbstractInterceptor {
    public function invoke(S2Container_MethodInvocation $invocation){
        $start  = $this->microtime_float();
        $result = $invocation->proceed();
        $stop   = $this->microtime_float();
        $time   = $stop - $start;
        print "execute time : $time" . PHP_EOL;
        return $result;
    }
    private function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}
