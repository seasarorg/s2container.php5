<?php
class ClassUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetClassSource() {
       
        print __METHOD__ . "\n";
       
        $uRef = new ReflectionClass('U');
        $src = S2Container_ClassUtil::getClassSource($uRef);

        $this->assertEqual(trim($src[0]),"class U{");       
        $this->assertEqual(trim($src[3]),"}");       
        print "\n";
    }

    function testGetSource() {
       
        print __METHOD__ . "\n";
        $uRef = new ReflectionClass('U');
        $src = S2Container_ClassUtil::getSource($uRef);
        $this->assertEqual(trim($src[2]),"class U{");       
        $this->assertEqual(trim($src[5]),"}");       

        print "\n";
    }

    function testGetMethod() {
       
        print __METHOD__ . "\n";
       
        $cRef = new ReflectionClass('C');
        $mRef = S2Container_ClassUtil::getMethod($cRef,'say');
        $this->assertEqual($mRef->getName(),"say");       

        try{
            $mRef = S2Container_ClassUtil::getMethod($cRef,'say2');
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_NoSuchMethodRuntimeException');	
        }
        print "\n";
    }

    function testHasMethod() {
       
        print __METHOD__ . "\n";
       
        $cRef = new ReflectionClass('C');
        $this->assertTrue(S2Container_ClassUtil::hasMethod($cRef,'say'));
        $this->assertTrue(!S2Container_ClassUtil::hasMethod($cRef,'say2'));

        print "\n";
    }

    function testGetInterfaces() {
       
        print __METHOD__ . "\n";
       
        $cRef = new ReflectionClass('G');
        $this->assertEqual(count(S2Container_ClassUtil::getInterfaces($cRef)),1);

        $cRef = new ReflectionClass('IW');
        $this->assertEqual(count(S2Container_ClassUtil::getInterfaces($cRef)),2);

        print "\n";
    }
}
?>