<?php
class Hello {
    private $printer;
    /**
     * @S2Binding('console')
     */
    public function setPrinter($printer) {
        $this->printer = $printer;
    }
    public function sayHello() {
        $this->printer->printOut('Hello World ! with DI');
    }
}
