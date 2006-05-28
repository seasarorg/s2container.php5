<?php
class S2ContainerFactoryTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetBuilder() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test1.dicon');
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test1.dicon');

        print "\n";
    }
}
?>