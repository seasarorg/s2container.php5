<?php
class BarAllTest {

    public static function group() {
        $group = new GroupTest('Bar Test');

        $group->addTestCase(new BarLogicTest());

        return $group;    	
    }
}
?>