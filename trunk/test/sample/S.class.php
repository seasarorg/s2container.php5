<?php

class S {
    private $val1;
    private $val2;
    private $refO;
    private $refG;
    
    function S($val1="",IO $o) {
        $this->val1 = $val1;
        $this->refO = $o;
    }
    
    function getRefO(){
        return $this->refO;
    }    

    function setRefG(IG $g){
        $this->refG = $g;
    }    
    function getRefG(){
        return $this->refG;
    }    

    function getVal1(){
        return $this->val1;
    }    

    function setVal2($val2=""){
        $this->val2 = $val2;
    }    
    function getVal2(){
        return $this->val2;
    }    
}
?>
