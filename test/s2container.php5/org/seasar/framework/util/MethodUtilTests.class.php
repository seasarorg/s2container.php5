<?php
class MethodUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetSource() {
       
        print __METHOD__ . "\n";
       
        $c = new ReflectionClass('C');
        $src = ClassUtil::getSource($c);
        $m = $c->getMethod('say');
        $src = MethodUtil::getSource($m,$src);

        $this->assertEqual(trim($src[0]),"public function say(){");       
        $this->assertEqual(trim($src[2]),"}");     

        $src = MethodUtil::getSource($m);
        $this->assertEqual(trim($src[0]),"public function say(){");       
        $this->assertEqual(trim($src[2]),"}");     

        $ref = new ReflectionClass('IW');
        $src = ClassUtil::getSource($ref);
        $m = $ref->getMethod('wm1');
        $src = MethodUtil::getSource($m,$src);
        $this->assertEqual(trim($src[0]),'function wm1($arg1,IA &$a);');     
       
        print "\n";
    }

    function testInvoke() {
       
        print __METHOD__ . "\n";

        $t = new MethodUtilTest();
        $ref = new ReflectionClass('MethodUtilTest');
        
        $m = $ref->getMethod('a');
        $ret = MethodUtil::invoke($m,$t,array());
        $this->assertTrue($ret);

        $m = $ref->getMethod('a');
        $ret = MethodUtil::invoke($m,$t,null);
        $this->assertTrue($ret);

        $m = $ref->getMethod('b');
        $ret = MethodUtil::invoke($m,$t,array('hoge'));
        $this->assertEqual($ret,'hoge');

        $m = $ref->getMethod('c');
        $ret = MethodUtil::invoke($m,$t,array(1,2));
        $this->assertEqual($ret,3);

        $m = $ref->getMethod('d');
        $ret = MethodUtil::invoke($m,$t,array(array(1,2)));
        $this->assertEqual($ret,3);

        $m = $ref->getMethod('e');
        $ret = MethodUtil::invoke($m,$t,array(array(1,2)));
        $this->assertEqual($ret,3);

        $m = $ref->getMethod('f');
        $ret = MethodUtil::invoke($m,$t,array(array(new A(),new B())));
        $this->assertIsA($ret,'B');

        print "\n";
    }
}

class MethodUtilTest {

    function MethodUtilTest(){}
	
	function a(){
	    return true;	
	}

	function b($arg1){
	    return $arg1;	
	}

	function c($arg1,$arg2){
	    return $arg1 + $arg2;	
	}

	function d($arg){
	    return $arg[0] + $arg[1];	
	}

	function e(&$arg){
	    return $arg[0] + $arg[1];	
	}

	function f(&$arg){
	    return $arg[1];	
	}
}
?>