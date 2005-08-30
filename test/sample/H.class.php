<?php

class H {
    private $g;
    private $name;
    function H(IG $g) {
        $this->g = $g;
    }
    
    function getItem(){
        return $this->g;    
    }

    function setName($name){
        $this->name = $name;    
    }
    function getName(){
        return $this->name;    
    }

    function setG(IG $g){
        $this->g = $g;
    }
    function getG(){
        return $this->g;
    }
    
}
?>
