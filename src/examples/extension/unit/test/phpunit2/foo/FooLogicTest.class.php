<?php
class FooLogicTest extends S2Container_S2PHPUnit2TestCase {

    public $logic;
    
    function __construct($name) {
    	parent::__construct($name);
    }

    function setUp(){
        print "\nTEST : ".get_class($this)."::".$this->getName()."\n";
        print "------------------------------------\n";   

        $this->includeDicon(UNIT_EXAMPLE . "/src/foo/foo.dicon");
    }

    function tearDown(){
        print "\n";
    }

    function testShowYear(){
        $year = $this->logic->showYear();
        $this->assertEquals($year , 2005);	
    }
}
?>