<?php
class FooAllTest {

    public static function group() {
        $group = new GroupTest('Foo Test');

        $group->addTestCase(new FooLogicTest());

        return $group;    	
    }
}
?>