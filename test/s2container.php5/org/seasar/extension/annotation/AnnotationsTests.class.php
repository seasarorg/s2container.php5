<?php
class AnnotationsTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetAnnotations() {
       
        print __METHOD__ . "\n";

        $annos = S2Container_Annotations::getAnnotations('A_AnnotationsTests',
                                                         'm01');
        
        $anno = $annos['Annotation01_AnnotationsTests'];
        $this->assertEqual($anno->name,'hoge'); 

        $annos = S2Container_Annotations::getAnnotations(
                           new ReflectionClass('A_AnnotationsTests'),
                                                              'm02');
        
        $anno = $annos['Annotation01_AnnotationsTests'];
        $this->assertEqual($anno->name,'hoge'); 
        $this->assertEqual($anno->year,'2000'); 

        $anno = $annos['Annotation02_AnnotationsTests'];
        $this->assertEqual($anno->value[0],'hoge'); 
        $this->assertEqual($anno->value[1],'2000'); 
        
        print "\n";
    }

    function testGetAnnotation() {
        print __METHOD__ . "\n";

        $anno = S2Container_Annotations::getAnnotation(
                         'Annotation01_AnnotationsTests',
                         'A_AnnotationsTests',
                         'm02');
        $this->assertEqual($anno->name,'hoge'); 
        $this->assertEqual($anno->year,'2000'); 

        try{
            $anno = S2Container_Annotations::getAnnotation(
                         'Annotation03_AnnotationsTests',
                         'A_AnnotationsTests',
                         'm02');
            
            $this->assertTrue(false);
        }catch(Exception $e){
            $this->assertTrue(true);
            print "{$e->getMessage()}\n";
        }
        print "\n";
    }

    function testIsAnnotationPresent() {
        print __METHOD__ . "\n";

        $ret = S2Container_Annotations::isAnnotationPresent(
                         'Annotation01_AnnotationsTests',
                         'A_AnnotationsTests',
                         'm02');
        $this->assertEqual($ret,true); 

        $ret = S2Container_Annotations::isAnnotationPresent(
                         'Annotation03_AnnotationsTests',
                         'A_AnnotationsTests');
            
        $this->assertFalse($ret);

        print "\n";
    }
}

class A_AnnotationsTests{

    /**
     * @Annotation01_AnnotationsTests
     */
    public function m01(){
        
    }    

    /**
     * @Annotation01_AnnotationsTests(name=hoge,year=2000)
     * @Annotation02_AnnotationsTests(hoge,2000)
     */
    public function m02(){
        
    }    

}

class Annotation01_AnnotationsTests {
    public $name = "hoge";   
}
class Annotation02_AnnotationsTests {}
?>