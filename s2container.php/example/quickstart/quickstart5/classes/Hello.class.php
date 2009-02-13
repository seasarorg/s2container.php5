<?php
class Hello {
    private $printer;
    public function setPrinter(Printer $printer) {
        $this->printer = $printer;
    }
    public function sayHello() {
        $this->printer->printOut('Hello World ! with DI');
    }
}
