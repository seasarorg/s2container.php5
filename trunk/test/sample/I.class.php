<?php

class I {

    private $result = -1;
    
    function I() {
    }
    
    function culc(){
        $this->result = 1+1;
    }

    function culc2($a,$b){

        $this->result = $a+$b;
    }

    function culc3(IG $d){
        if($d instanceof D){
            $this->result = 4;
        }else{return -1;}
    }
    
    function getResult(){
        return $this->result;
    }
}
?>
