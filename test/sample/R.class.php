<?php

class R {
 
    private $val1;
    private $val2;
    
    function R($val1,$val2) {
        $this->val1 = $val1;
        $this->val2 = $val2;
    }
    
    function setVal1($val1){
        $this->val1 = $val1;
    }

    function setVal2($val2){
        $this->val2 = $val2;
    }

    function getVal1(){
        return $this->val1;
    }

    function getVal2(){
        return $this->val2;
    }

    function finish($msg){
        print "destroy method called. arg = $msg\n";
    }

}
?>
