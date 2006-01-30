<?php
class CommentAnnotationReaderTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $reader = new S2Container_CommentAnnotationReader();
        $this->assertIsA($reader,'S2Container_CommentAnnotationReader');

        print "\n";
    }

    function testGetAnnotations01() {
        print __METHOD__ . "\n";

        $reader = new S2Container_CommentAnnotationReader();
        $annos = $reader->getAnnotations(
            new ReflectionClass('A_CommentAnnotationReaderTests'),null);

        $this->assertEqual($annos,array());
        print "\n";
    }

    function testGetAnnotations02() {
        print __METHOD__ . "\n";

        $reader = new S2Container_CommentAnnotationReader();
        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),null);

        $this->assertEqual(count($annos),1);
        $this->assertIsA($annos['Annotation01_CommentAnnotationReaderTests'],
                         'Annotation01_CommentAnnotationReaderTests');

        print "\n";
    }

    function testGetAnnotations03() {
        print __METHOD__ . "\n";

        $reader = new S2Container_CommentAnnotationReader();

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m01');
        $this->assertEqual(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m02');
        $this->assertIsA($annos['Annotation01_CommentAnnotationReaderTests'],
                         'Annotation01_CommentAnnotationReaderTests');

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m03');
        $this->assertEqual(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m04');
        $this->assertEqual(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m05');
        $this->assertEqual(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m06');
        $this->assertIsA($annos['Annotation01_CommentAnnotationReaderTests'],
                         'Annotation01_CommentAnnotationReaderTests');

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m07');
        $this->assertEqual(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m08');
        $this->assertEqual(count($annos),0);


        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m09');
        $this->assertEqual(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m10');
        $this->assertIsA($annos['Annotation01_CommentAnnotationReaderTests'],
                         'Annotation01_CommentAnnotationReaderTests');

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m11');
        $this->assertEqual(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReaderTests'),'m12');
        $this->assertEqual(count($annos),0);

        print "\n";
    }

    function testGetAnnotations04() {
        print __METHOD__ . "\n";

        $reader = new S2Container_CommentAnnotationReader();

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m01');
        $this->assertEqual(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m02');
        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m03');
        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m04');
        $this->assertEqual(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m05');
        $this->assertEqual(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m06');
        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m07');
        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m08');
        $this->assertEqual(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m09');
        $this->assertEqual(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m10');
        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m11');
        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReaderTests'),'m12');

        print "\n";
    }

    function testGetAnnotations05() {
        print __METHOD__ . "\n";

        $reader = new S2Container_CommentAnnotationReader();

        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReaderTests'),'m01');
        $this->assertEqual(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->name,'hoge');
        $this->assertEqual($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');
        $this->assertEqual($anno->value[1],'2000');


        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReaderTests'),'m02');
        $this->assertEqual(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->name,'hoge');
        $this->assertEqual($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value,'hoge');


        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReaderTests'),'m03');
        $this->assertEqual(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->name,'hoge');
        $this->assertEqual($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');
        $this->assertEqual($anno->value[1],'2000');

        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReaderTests'),'m04');
        $this->assertEqual(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->name,'hoge');
        $this->assertEqual($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');
        $this->assertEqual($anno->value[1],'2000');

        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReaderTests'),'m05');
        $this->assertEqual(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->name,'hoge');
        $this->assertEqual($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');
        $this->assertEqual($anno->value[1],'2000');

        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReaderTests'),'m06');
        $this->assertEqual(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->name,'hoge');
        $this->assertEqual($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReaderTests'];
        $this->assertEqual($anno->value[0],'hoge');
        $this->assertEqual($anno->value[1],'2000');
        print "\n";
    }

    function testGetAnnotations06() {
        print __METHOD__ . "\n";

        $reader = new S2Container_CommentAnnotationReader();

        try{
            $annos = $reader->getAnnotations(
                new ReflectionClass('E_CommentAnnotationReaderTests'),'m01');
        }catch(Exception $e){
            print "{$e->getMessage()}\n";    
        }
        
        print "\n";
    }

}
    
/**
 * 
 */
class A_CommentAnnotationReaderTests{}
    
/**
 * hoge hoge
 * @Annotation01_CommentAnnotationReaderTests
 */    
class B_CommentAnnotationReaderTests{

    /** xxx */
    public function m01(){}

    /**   @Annotation01_CommentAnnotationReaderTests  */
    public function m02(){}

    /** @Annotation01_CommentAnnotationReaderTests  xxx */
    public function m03(){}

    /** xxx @Annotation01_CommentAnnotationReaderTests */
    public function m04(){}


    /** xxx
     */
    public function m05(){}

    /**   @Annotation01_CommentAnnotationReaderTests
     */
    public function m06(){}

    /** @Annotation01_CommentAnnotationReaderTests  xxx 
     */
    public function m07(){}

    /** xxx @Annotation01_CommentAnnotationReaderTests 
     */
    public function m08(){}


    /** 
     * xxx */
    public function m09(){}

    /**   
     *    @Annotation01_CommentAnnotationReaderTests  */
    public function m10(){}

    /** 
     *    @Annotation01_CommentAnnotationReaderTests  xxx */
    public function m11(){}

    /** 
     * xxx @Annotation01_CommentAnnotationReaderTests */
    public function m12(){}
}

class C_CommentAnnotationReaderTests{

    /** xxx */
    public function m01(){}

    /**   @Annotation01_CommentAnnotationReaderTests ('hoge','2000') */
    public function m02(){}

    /** @Annotation01_CommentAnnotationReaderTests  ('hoge','2000')xxx */
    public function m03(){}

    /** xxx @Annotation01_CommentAnnotationReaderTests ('hoge','2000')*/
    public function m04(){}


    /** xxx
     */
    public function m05(){}

    /**   @Annotation01_CommentAnnotationReaderTests ('hoge','2000')
     */
    public function m06(){}

    /** @Annotation01_CommentAnnotationReaderTests('hoge','2000')  xxx 
     */
    public function m07(){}

    /** xxx @Annotation01_CommentAnnotationReaderTests('hoge','2000')
     */
    public function m08(){}


    /** 
     * xxx */
    public function m09(){}

    /**   
     *    @Annotation01_CommentAnnotationReaderTests('hoge','2000')  */
    public function m10(){}

    /** 
     *    @Annotation01_CommentAnnotationReaderTests ('hoge','2000') xxx */
    public function m11(){}

    /** 
     * xxx @Annotation01_CommentAnnotationReaderTests('hoge','2000') */
    public function m12(){}
}


class D_CommentAnnotationReaderTests{
    /** 
     * @Annotation02_CommentAnnotationReaderTests(name=>'hoge',year=>'2000')
     * @Annotation01_CommentAnnotationReaderTests('hoge','2000')
     */
    public function m01(){}

    /** 
     * @Annotation02_CommentAnnotationReaderTests(name=>hoge , 'year'=>2000)
     * @Annotation01_CommentAnnotationReaderTests('hoge')
     */
    public function m02(){}

    /** 
     * @Annotation02_CommentAnnotationReaderTests(
     *                    name=>'hoge',year=>'2000')
     * @Annotation01_CommentAnnotationReaderTests('hoge',
     *                                            '2000')
     */
    public function m03(){}

    /** 
     * @Annotation02_CommentAnnotationReaderTests(
     *                    name=>'hoge',
     * 
     *                    year=>'2000')
     * @Annotation01_CommentAnnotationReaderTests('hoge',
     * 
     *                                            '2000')
     */
    public function m04(){}

    /** 
     * Zzzz Zzzz Zzzz
     * Zzzz Zzzz Zzzz
     * @Annotation02_CommentAnnotationReaderTests(
     *                    name=>
     *                    'hoge',
     *                    year=>'2000')
     * Zzzz Zzzz Zzzz
     * @Annotation01_CommentAnnotationReaderTests('hoge',
     *                                            '2000')
     * Zzzz Zzzz Zzzz
     * Zzzz Zzzz Zzzz
     */
    public function m05(){}

    /** 
     * @Zzzz Zzzz Zzzz
     * @Zzzz Zzzz Zzzz
     * @Annotation02_CommentAnnotationReaderTests(
     *                    name=>
     *                    'hoge',
     *                    year=>'2000')
     * @Zzzz Zzzz Zzzz
     * @Annotation01_CommentAnnotationReaderTests('hoge',
     *                                            '2000')
     * @Zzzz Zzzz Zzzz
     * @Zzzz Zzzz Zzzz
     */
    public function m06(){}
}

class E_CommentAnnotationReaderTests{
    /** 
     * @Annotation02_CommentAnnotationReaderTests(name=>'hoge',year,'2000')
     */
    public function m01(){}

}
class Annotation01_CommentAnnotationReaderTests{}
class Annotation02_CommentAnnotationReaderTests{}


?>