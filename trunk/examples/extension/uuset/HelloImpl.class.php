<?php
class HelloImpl {

    private $messageA = "";
    private $messageB = "";

    function HelloImpl() {}
    
    function setMessageA($messageA){
        $this->messageA = $messageA;	
    }

    function __set($name,$val){
        $this->$name = $val;	
    }
    
    function showMessage(){
        print "{$this->messageA} {$this->messageB} \n";
    }
}
?>