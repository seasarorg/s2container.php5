<?php
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
} 

interface Foo{
    public function test(); 
}
class FooImpl implements Foo {
	public function test(){
        print __CLASS__ . PHP_EOL; 
    }   
}
interface Bar{
    public function test();	
}
class BarImpl implements Bar {
    public function test(){
        print __CLASS__ . ' called.' . PHP_EOL; 
    }	
}
?>