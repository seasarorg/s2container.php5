<?php
class GreetingClientImpl 
    implements GreetingClient {

    private $greeting;

    public function setGreeting(Greeting $greeting) {
        $this->greeting = $greeting;
    }
    
    public function execute() {
        print "{$this->greeting->greet()}\n";
    }
}
?>
