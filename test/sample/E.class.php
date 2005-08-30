<?php

class E {
    private $d;
    private $name;
    function E(IG $d) {
        $this->d = $d;
    }
    
    function getItem(){
        return $this->d;    
    }

    function setName($name){
        $this->name = $name;    
    }
    function getName(){
        return $this->name;    
    }

    function setD(D $d){
        $this->d = $d;
    }
    function getD(){
        return $this->d;
    }
    
}
?>
