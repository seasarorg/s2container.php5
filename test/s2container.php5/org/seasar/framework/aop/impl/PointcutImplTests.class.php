<?php
class PointcutImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testNullConstructArg() {
       
        print __METHOD__ . "\n";

        try{
        	$pc = new PointcutImpl();       
        }catch(Exception $e){
        	$this->assertIsA($e,'EmptyRuntimeException');
        }

        try{
        	$pc = new PointcutImpl(array());       
        }catch(Exception $e){
        	$this->assertIsA($e,'EmptyRuntimeException');
        }
               
        print "\n";
    }  

    function testIsApplied() {
       
        print __METHOD__ . "\n";
       
        $pc = new PointcutImpl(array('pm1','pm2','om1','om2'));
        $this->assertTrue($pc->isApplied('pm1'));       
        $this->assertTrue($pc->isApplied('pm2'));       
        $this->assertTrue($pc->isApplied('om1'));       
        $this->assertTrue($pc->isApplied('om2'));       

        $pc = new PointcutImpl(new ReflectionClass('AW'));
        $this->assertTrue($pc->isApplied('wm1'));       
        $this->assertTrue($pc->isApplied('wm2'));       
        $this->assertTrue($pc->isApplied('om1'));       
        $this->assertTrue($pc->isApplied('om2'));       
        $this->assertTrue($pc->isApplied('awm1'));       

        $pc = new PointcutImpl(new ReflectionClass('C'));
        $this->assertTrue($pc->isApplied('say') == false);       

        $pc = new PointcutImpl(array('^a','b$'));
        $this->assertTrue($pc->isApplied('abs'));       
        $this->assertTrue($pc->isApplied('deb'));       
        $this->assertTrue($pc->isApplied('om') == false);       

        $pc = new PointcutImpl(array('!^a','!b$'));
        $this->assertTrue($pc->isApplied('abs'));       
        $this->assertTrue($pc->isApplied('deb'));       
        $this->assertTrue($pc->isApplied('om'));       
       
        print "\n";
    }

}
?>
