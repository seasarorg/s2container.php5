<?php
class FooLogicTest extends S2PHPUnitTestCase {

    public $logic;
    
    function __construct($name) {
    	parent::__construct($name);
    }    

    function setUp(){
        $this->includeDicon(UNIT_EXAMPLE . "/src/foo/foo.ini");
    }
    
    function tearDown(){}

    function testShowYear(){
        $msg = $this->logic->showYear();
        $this->assertEquals($msg , 2005);	
    }

}
?>