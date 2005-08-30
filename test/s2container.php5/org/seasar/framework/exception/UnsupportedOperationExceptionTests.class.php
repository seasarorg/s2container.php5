<?php
class UnsupportedOperationExceptionTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testException() {
       
        print __METHOD__ . "\n";

        try{
            throw new 	UnsupportedOperationException("<unsupport exception test>");
        }catch(Exception $e){
        	print $e->getMessage() . "\n";
        }

        print "\n";
    } 
}
?>