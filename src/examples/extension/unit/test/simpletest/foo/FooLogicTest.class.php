<?php
class FooLogicTest extends S2SimpleTestCase {

    public $logic;
    
    function __construct($name="") {
    	parent::__construct($name);
    }

    function setUp(){
        print "\nTEST : ".get_class($this)."::".$this->getName()."\n";
        print "------------------------------------\n";   

        $this->includeDicon(UNIT_EXAMPLE . "/src/foo/foo.ini");
    }

    function tearDown(){
        print "\n";
    }

    function testShowYear(){
        $year = $this->logic->showYear();
        $this->assertEqual($year , 2005);	
    }
}
?>