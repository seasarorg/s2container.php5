<?php
class AnnotationFactoryTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testCreate() {
       
        print __METHOD__ . "\n";

        $annoObj = S2Container_AnnotationFactory::create('A_AnnotationFactoryTests');
        $this->assertEqual($annoObj,null);

        $annoObj = S2Container_AnnotationFactory::create('B_AnnotationFactoryTests');
        $this->assertIsA($annoObj,'B_AnnotationFactoryTests');

        $annoObj = S2Container_AnnotationFactory::create('C_AnnotationFactoryTests',
                     array('hoge'));
        $this->assertIsA($annoObj,'C_AnnotationFactoryTests');
        $this->assertEqual($annoObj->value,'hoge');

        $annoObj = S2Container_AnnotationFactory::create('C_AnnotationFactoryTests',
                     array('hoge','2005'));
        $this->assertEqual($annoObj->value,array('hoge','2005'));

        $annoObj = S2Container_AnnotationFactory::create('C_AnnotationFactoryTests',
                     array('name'=>'hoge','year'=>'2005'),
                     S2Container_AnnotationFactory::ARGS_TYPE_HASH);
        $this->assertEqual($annoObj->name,'hoge');
        $this->assertEqual($annoObj->year,'2005');

        print "\n";
    }
}

class B_AnnotationFactoryTests {}

class C_AnnotationFactoryTests {}

?>