<?php
class RootHelloClient implements HelloClient {

    private $hello_;

    public function setHello(Hello $hello) {
        $this->hello_ = $hello;
    }

    public function getHello() {
        return $this->hello_;
    }

    public function showMessage() {
        print $this->getHello()->getMessage() . "\n";
    }
}
?>
