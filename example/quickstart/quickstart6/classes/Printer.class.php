<?php
class Printer {
    /**
     * @S2Aspect('interceptor' => 'new seasar::aop::interceptor::TraceInterceptor')
     */
    public function printOut($message) {
        print __METHOD__ . ' : ' . $message . PHP_EOL;
    }
}