<?php

class M2 {
    private $val;
    
    function __set($name,$value){
        print __METHOD__ . " called.\n";
        $this->$name = $value;   
        print "property : $name, value : $value \n" ;
    }
    
    function getValue(){
        return $this->val;	
    }
    
    function M2() {
    }   
}
?>
