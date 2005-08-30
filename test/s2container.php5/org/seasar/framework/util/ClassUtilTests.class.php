<?php
class ClassUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetClassSource() {
       
        print __METHOD__ . "\n";
       
        $uRef = new ReflectionClass('U');
        $src = ClassUtil::getClassSource($uRef);

        $this->assertEqual(trim($src[0]),"class U{");       
        $this->assertEqual(trim($src[3]),"}");       
        print "\n";
    }

    function testGetSource() {
       
        print __METHOD__ . "\n";
        $uRef = new ReflectionClass('U');
        $src = ClassUtil::getSource($uRef);
        $this->assertEqual(trim($src[2]),"class U{");       
        $this->assertEqual(trim($src[5]),"}");       

        print "\n";
    }

    function testGetMethod() {
       
        print __METHOD__ . "\n";
       
        $cRef = new ReflectionClass('C');
        $mRef = ClassUtil::getMethod($cRef,'say');
        $this->assertEqual($mRef->getName(),"say");       

        try{
            $mRef = ClassUtil::getMethod($cRef,'say2');
        }catch(Exception $e){
            $this->assertIsA($e,'NoSuchMethodRuntimeException');	
        }
        print "\n";
    }

    function testHasMethod() {
       
        print __METHOD__ . "\n";
       
        $cRef = new ReflectionClass('C');
        $this->assertTrue(ClassUtil::hasMethod($cRef,'say'));
        $this->assertTrue(!ClassUtil::hasMethod($cRef,'say2'));

        print "\n";
    }

    function testGetInterfaces() {
       
        print __METHOD__ . "\n";
       
        $cRef = new ReflectionClass('G');
        $this->assertEqual(count(ClassUtil::getInterfaces($cRef)),1);

        $cRef = new ReflectionClass('IW');
        $this->assertEqual(count(ClassUtil::getInterfaces($cRef)),2);

        print "\n";
    }
}
?>