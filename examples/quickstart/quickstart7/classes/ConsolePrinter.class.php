<?php
/**
 * @S2Component('name' => 'console')
 */
class ConsolePrinter implements Printer {
    public function printOut($message) {
        print __METHOD__ . ' : ' . $message . PHP_EOL;
    }
}
