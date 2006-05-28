<?php
class BarLogicTest extends S2Container_S2SimpleTestCase {

    public $logic;
    
    function __construct($name="") {
    	parent::__construct($name);
    }    

    function setUp(){
        print "\nTEST : ".get_class($this)."::".$this->getName()."\n";
        print "------------------------------------\n";   
        $this->includeDicon(UNIT_EXAMPLE . "/src/bar/bar.dicon");
    }
    
    function tearDown(){
        print "\n";
    }
    
    function testShowMessage(){
        $msg = $this->logic->showMessage();
        $this->assertEqual($msg , 'bar bar');	
    }

}
?>