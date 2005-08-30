<?php

class C {
    private $name;
    function C($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}
?>
