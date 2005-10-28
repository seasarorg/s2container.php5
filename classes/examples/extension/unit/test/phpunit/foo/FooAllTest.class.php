<?php
class FooAllTest {

    public static function suite() {
        $suite  = new PHPUnit_TestSuite();

        $suite->addTestSuite('FooLogicTest');

        return $suite;    	
    }
}
?>