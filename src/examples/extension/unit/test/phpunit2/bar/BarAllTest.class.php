<?php
class BarAllTest {

    public static function main() {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite();

        $suite->addTestSuite('BarLogicTest');

        return $suite;    	
    }
}
?>