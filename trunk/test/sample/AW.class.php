<?php

abstract class AW implements IW{

    function AW() {
    }
 
    function om1(){
        print __METHOD__ . " called.\n";    
    }

    function om2(){
        print __METHOD__ . " called.\n";    
    }

    public function wm1($arg1,IA &$a){
        print __METHOD__ . " called.\n";    
    }

    function wm2(){
        print __METHOD__ . " called.\n";    
    }
    
    abstract function awm1();
    
}
?>
