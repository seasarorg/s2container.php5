<?php
class BarLogicTest extends S2PHPUnitTestCase {

    public $logic;
    
    function __construct($name) {
    	parent::__construct($name);
    }    

    function setUp(){
        $this->includeDicon(UNIT_EXAMPLE . "/src/bar/bar.ini");
    }
    
    function tearDown(){}

    function testShowMessage(){
        $msg = $this->logic->showMessage();
        $this->assertEquals($msg , 'bar bar');	
    }

}
?>