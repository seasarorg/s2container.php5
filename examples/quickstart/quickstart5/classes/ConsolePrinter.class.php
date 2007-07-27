<?php
class ConsolePrinter implements Printer {
    public function printOut($message) {
        print __METHOD__ . ' : ' . $message . PHP_EOL;
    }
}
