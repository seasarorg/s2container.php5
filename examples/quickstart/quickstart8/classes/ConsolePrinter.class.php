<?php
/**
 * @S2Component('name' => 'console')
 */
class ConsolePrinter implements Printer {

    /**
     * @S2Aspect('interceptor' => 'TimeInterceptor')
     */
    public function printOut($message) {
        print __METHOD__ . ' : ' . $message . PHP_EOL;
    }
}
