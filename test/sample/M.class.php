<?php

class M {
    private $name;
    
    function __set($name,$value){
        print __METHOD__ . " called.\n";
       $this->$name = $value;    
    }
    
    function M() {
    }
    
    function getName(){
        return $this->name;
    }
}
?>
