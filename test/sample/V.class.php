<?php

class V {

    private $container;
    
    function V() {
    }
    
    function setContainer(S2Container $container){
        $this->container = $container;
    }
    
    function getContainer(){
        return $this->container;
    }
}
?>
