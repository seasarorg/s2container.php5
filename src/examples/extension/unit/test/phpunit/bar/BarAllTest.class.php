<?php
class BarAllTest {

    public static function suite() {
        $suite  = new PHPUnit_TestSuite();

        $suite->addTestSuite('BarLogicTest');

        return $suite;    	
    }
}
?>