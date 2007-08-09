<?php
class ConsolePrinter {
    public function printOut($message) {
        print __METHOD__ . ' : ' . $message . PHP_EOL;
    }
}
