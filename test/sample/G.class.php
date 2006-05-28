<?php

class G implements IG {

    function G() {
    }
    
    function finish(){
        print "destroy class G \n";
    }

    function finish2($msg){
        print "$msg G \n";
    }

}
?>
