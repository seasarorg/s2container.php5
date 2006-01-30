<?php
class AnnotationContainerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
       
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $this->assertIsA($container,'S2Container_AnnotationContainer');
         
        print "\n";
    }

    function testAnnotationId() {
       
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $id = $container->getAnnotationId("Foo","bar");
        $this->assertEqual($id,'Foo_bar');

        $id = $container->getAnnotationId("Foo",null);
        $this->assertEqual($id,'Foo_class');

        $id = $container->getAnnotationId(null,null);
        $this->assertEqual($id,'_class');
         
        print "\n";
    }
}

?>