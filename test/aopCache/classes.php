<?php
class A{
    function test($a,$b){
    	print "test called.\n";
    	return $a + $b;
    }	
}

interface IB {
	function test($a,$b);
}

class B implements IB{
    function test($a,$b){
    	print "test called.\n";
    	return $a + $b;
    }	
}

?>
