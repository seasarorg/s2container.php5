<?php
class Printer {
    /**
     * @S2Aspect('interceptor' => 'new S2Container_TraceInterceptor')
     */
    public function printOut($message) {
        print __METHOD__ . ' : ' . $message . PHP_EOL;
    }
}