<?php
class PointcutImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testNullConstructArg() {
       
        print __METHOD__ . "\n";

        try{
        	$pc = new S2Container_PointcutImpl();       
        }catch(Exception $e){
        	$this->assertIsA($e,'S2Container_EmptyRuntimeException');
        }

        try{
        	$pc = new S2Container_PointcutImpl(array());       
        }catch(Exception $e){
        	$this->assertIsA($e,'S2Container_EmptyRuntimeException');
        }
               
        print "\n";
    }  

    function testIsApplied() {
       
        print __METHOD__ . "\n";
       
        $pc = new S2Container_PointcutImpl(array('pm1','pm2','om1','om2'));
        $this->assertTrue($pc->isApplied('pm1'));       
        $this->assertTrue($pc->isApplied('pm2'));       
        $this->assertTrue($pc->isApplied('om1'));       
        $this->assertTrue($pc->isApplied('om2'));       

        $pc = new S2Container_PointcutImpl(new ReflectionClass('AW'));
//        $this->assertTrue($pc->isApplied('wm1'));       
//        $this->assertTrue($pc->isApplied('wm2'));       
//        $this->assertTrue($pc->isApplied('om1'));       
//        $this->assertTrue($pc->isApplied('om2'));       
        $this->assertTrue($pc->isApplied('awm1'));       

        $pc = new S2Container_PointcutImpl(new ReflectionClass('C'));
        $this->assertTrue($pc->isApplied('say') == false);       

        $pc = new S2Container_PointcutImpl(array('^a','b$'));
        $this->assertTrue($pc->isApplied('abs'));       
        $this->assertTrue($pc->isApplied('deb'));       
        $this->assertTrue($pc->isApplied('om') == false);       

        $pc = new S2Container_PointcutImpl(array('^(!?a)'));
        $this->assertTrue($pc->isApplied('abs'));       
        $this->assertFalse($pc->isApplied('deb'));       
        $this->assertFalse($pc->isApplied('om'));       

        $pc = new S2Container_PointcutImpl(array('(!?a)$'));
        $this->assertFalse($pc->isApplied('abs'));       
        $this->assertTrue($pc->isApplied('aba'));       
       
        print "\n";
    }

}
?>
