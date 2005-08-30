<?php
class BarLogicTest extends S2PHPUnit2TestCase {

    public $logic;
    
    function __construct($name) {
    	parent::__construct($name);
    }

    function setUp(){
        print "\nTEST : ".get_class($this)."::".$this->getName()."\n";
        print "------------------------------------\n";   

        $this->includeDicon(UNIT_EXAMPLE . "/src/bar/bar.ini");
    }

    function tearDown(){
        print "\n";
    }

    function testShowMessage(){
        $msg = $this->logic->showMessage();
        $this->assertEquals($msg , 'bar bar');	
    }
}
?>