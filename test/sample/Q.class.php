<?php

class Q {

    function Q() {
    }
    
    function throwE(){
        throw new UnsupportedOperationException("throwE");
    }
    
    function doNone(){
        
        print "void method called.";    
        throw new UnsupportedOperationException("throwE");
    }
    
}
?>
