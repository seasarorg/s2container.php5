<?php
class HashMap implements Map{
    private $pool = array();
    
    function HashMap() {
    }
    
    function put($key,$val){
    	$this->pool[$key] = $val;
    }
    
    function get($key){
    	return array_key_exists($key,$this->pool) ? $this->pool[$key] : null;
    }
}
?>