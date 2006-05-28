<?php

class Q {

    function Q() {
    }
    
    function throwE(){
        throw new S2Container_UnsupportedOperationException("throwE");
    }
    
    function doNone(){
        
        print "void method called.";    
        throw new S2Container_UnsupportedOperationException("throwE");
    }
    
}
?>
