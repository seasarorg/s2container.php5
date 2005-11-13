<?php
class MethodUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetSource() {
       
        print __METHOD__ . "\n";
       
        $c = new ReflectionClass('C');
        $src = S2Container_ClassUtil::getSource($c);
        $m = $c->getMethod('say');
        $src = S2Container_MethodUtil::getSource($m,$src);

        $this->assertEqual(trim($src[0]),"public function say(){");       
        $this->assertEqual(trim($src[2]),"}");     

        $src = S2Container_MethodUtil::getSource($m);
        $this->assertEqual(trim($src[0]),"public function say(){");       
        $this->assertEqual(trim($src[2]),"}");     

        $ref = new ReflectionClass('IW');
        $src = S2Container_ClassUtil::getSource($ref);
        $m = $ref->getMethod('wm1');
        $src = S2Container_MethodUtil::getSource($m,$src);
        $this->assertEqual(trim($src[0]),'function wm1($arg1,IA &$a);');     
       
        print "\n";
    }

    function testInvoke() {
       
        print __METHOD__ . "\n";

        $t = new MethodUtilTest();
        $ref = new ReflectionClass('MethodUtilTest');
        
        $m = $ref->getMethod('a');
        $ret = S2Container_MethodUtil::invoke($m,$t,array());
        $this->assertTrue($ret);

        $m = $ref->getMethod('a');
        $ret = S2Container_MethodUtil::invoke($m,$t,null);
        $this->assertTrue($ret);

        $m = $ref->getMethod('b');
        $ret = S2Container_MethodUtil::invoke($m,$t,array('hoge'));
        $this->assertEqual($ret,'hoge');

        $m = $ref->getMethod('c');
        $ret = S2Container_MethodUtil::invoke($m,$t,array(1,2));
        $this->assertEqual($ret,3);

        $m = $ref->getMethod('d');
        $ret = S2Container_MethodUtil::invoke($m,$t,array(array(1,2)));
        $this->assertEqual($ret,3);

        $m = $ref->getMethod('e');
        $ret = S2Container_MethodUtil::invoke($m,$t,array(array(1,2)));
        $this->assertEqual($ret,3);

        $m = $ref->getMethod('f');
        $ret = S2Container_MethodUtil::invoke($m,$t,array(array(new A(),new B())));
        $this->assertIsA($ret,'B');

        print "\n";
    }
    
    function testIllegalRelfection() {
       
        print __METHOD__ . "\n";

        try{
        	$ret = S2Container_MethodUtil::invoke(null,new A());
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_IllegalArgumentException');
            print "{$e->getMessage()}\n";
        }

        print "\n";
    }

    function testIllegalObject() {
       
        print __METHOD__ . "\n";

        $t = new MethodUtilTest();
        $ref = new ReflectionClass('MethodUtilTest');
        $m = $ref->getMethod('a');

        try{
        	$ret = S2Container_MethodUtil::invoke($m,null);
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_IllegalArgumentException');
            print "{$e->getMessage()}\n";
        }

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