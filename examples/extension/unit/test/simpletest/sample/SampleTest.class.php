<?php
class SampleTest extends S2Container_S2SimpleTestCase {

    public $a;
    
    function __construct() {
    	parent::__construct();
        print "\nTEST : " . get_class($this) . "\n";
        print "------------------------------------\n";   
    }

    function setUp(){
        print "setUp() called.\n";
        $this->includeDicon(UNIT_EXAMPLE . "/src/sample/sample.dicon");
    }

    function setUpTest1(){
        print "  setUpTest1() called.\n";
    }

	function setUpAfterContainerInit(){
        print "    setUpAfterContainerInit() called.\n";
	}

	function setUpAfterBindFields(){
        print "      setUpAfterBindFields() called.\n";
	}

    function tearDownBeforeUnbindFields() {
        print "      tearDownBeforeUnbindFields() called.\n";
    }

    function tearDownBeforeContainerDestroy() {
        print "    tearDownBeforeContainerDestroy() called.\n";
    }

    function tearDownTest1() {
        print "  tearDownTest1() called.\n";
    }

    function tearDown() {
        print "tearDown() called.\n\n";
    }

    function testTest1() {
       
        print "\n-----------------------------------\n";
        print __METHOD__ . "\n";
       
        print_r($this->a);
        
        print "-----------------------------------\n";
        print "\n";
    }

    function testTest2() {
       
        print "\n-----------------------------------\n";
        print __METHOD__ . "\n";
        print "-----------------------------------\n";
        print "\n";
    }
}
?>