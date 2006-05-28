<?php
class AnnotationContainerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
       
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $this->assertIsA($container,'S2Container_AnnotationContainer');

        $container2 = S2Container_AnnotationContainer::getInstance();         
        $this->assertReference($container,$container2);
         
        print "\n";
    }

    function testAnnotationId() {
       
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $id = $container->getAnnotationId("Foo","bar");
        $this->assertEqual($id,'Foo:bar');

        $id = $container->getAnnotationId("Foo",null);
        $this->assertEqual($id,'Foo:class');

        $id = $container->getAnnotationId(null,null);
        $this->assertEqual($id,':class');
         
        print "\n";
    }

    function testGetClassAnnotations() {
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $annos = $container->getAnnotations('A_AnnotationContainerTests');
        $this->assertEqual(count($annos),3);
        
        print "\n";
    }

    function testGetClassAnnotation() {
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $anno = $container->getAnnotation('AnnotationA_AnnotationContainerTests',
                                           'A_AnnotationContainerTests');
        $this->assertIsA($anno,'AnnotationA_AnnotationContainerTests');
        
        try{
            $anno = $container->getAnnotation('AnnotationC_AnnotationContainerTests',
                                           'A_AnnotationContainerTests');
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }
        
        print "\n";
    }

    function testIsClassAnnotationPresent() {
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $this->assertTrue($container->isAnnotationPresent(
                              'AnnotationA_AnnotationContainerTests',
                              'A_AnnotationContainerTests') );
        $this->assertFalse($container->isAnnotationPresent(
                              'AnnotationC_AnnotationContainerTests',
                              'A_AnnotationContainerTests') );       
        print "\n";
    }


    function testGetMethodAnnotations() {
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $annos = $container->getAnnotations('A_AnnotationContainerTests','m1');
        $this->assertEqual(count($annos),3);
        
        print "\n";
    }

    function testGetMethodAnnotation() {
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $anno = $container->getAnnotation('AnnotationA_AnnotationContainerTests',
                                           'A_AnnotationContainerTests'
                                           ,'m1');
        $this->assertIsA($anno,'AnnotationA_AnnotationContainerTests');
        
        try{
            $anno = $container->getAnnotation('AnnotationC_AnnotationContainerTests',
                                           'A_AnnotationContainerTests',
                                           'm1');
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }
        
        print "\n";
    }

    function testIsMethodAnnotationPresent() {
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $this->assertTrue($container->isAnnotationPresent(
                              'AnnotationA_AnnotationContainerTests',
                              'A_AnnotationContainerTests'),
                              'm1' );
        $this->assertFalse($container->isAnnotationPresent(
                              'AnnotationC_AnnotationContainerTests',
                              'A_AnnotationContainerTests'),
                              'm1' );       
        print "\n";
    }

    function testSameAnnotationObject() {
        print __METHOD__ . "\n";

        $container = S2Container_AnnotationContainer::getInstance();
        $anno1 = $container->getAnnotation(
                            'AnnotationA_AnnotationContainerTests',
                            'A_AnnotationContainerTests');

        $anno2 = $container->getAnnotation(
                            'AnnotationA_AnnotationContainerTests',
                            'A_AnnotationContainerTests');

        $anno3 = $container->getAnnotation(
                            'AnnotationA_AnnotationContainerTests',
                            'A_AnnotationContainerTests',
                            'm1' );

        $anno4 = $container->getAnnotation(
                            'AnnotationA_AnnotationContainerTests',
                            'A_AnnotationContainerTests',
                            'm1' );

        $this->assertReference($anno1,$anno2);
        $this->assertReference($anno3,$anno4);
        $this->assertTrue($anno1 === $anno2);
        $this->assertFalse($anno1 === $anno3);
        $this->assertFalse($anno2 === $anno4);

        print "\n";
    }

}

/**
 * @AnnotationA_AnnotationContainerTests
 * @AnnotationB_AnnotationContainerTests ()
 * @AnnotationC_AnnotationContainerTests (
 * @AnnotationD_AnnotationContainerTests ()
 * 
 */
class A_AnnotationContainerTests {

    public function __construct(){}
    
    /**
     * @AnnotationA_AnnotationContainerTests
     * @AnnotationB_AnnotationContainerTests ()
     * @AnnotationC_AnnotationContainerTests (
     * @AnnotationD_AnnotationContainerTests ()
     * 
     */
    public function m1(){}       
}

class AnnotationA_AnnotationContainerTests{}
class AnnotationB_AnnotationContainerTests{}
class AnnotationC_AnnotationContainerTests{}
class AnnotationD_AnnotationContainerTests{}
?>