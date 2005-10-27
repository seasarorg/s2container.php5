<?php

class W implements IW{

    function om1(){
        print __METHOD__ . " called.\n";    
    }

    function om2(){
        print __METHOD__ . " called.\n";    
    }

    function wm1($arg1,IA &$a){
        print __METHOD__ . " called.\n";    
    }

    function wm2(){
        print __METHOD__ . " called.\n";    
    }
}
?>
