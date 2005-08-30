<?php
class S2PHPUnitTestCaseTests extends S2PHPUnitTestCase {

    public $a;
    
    function __construct($name) {
    	parent::__construct($name);
    }

    function setUp(){
        print "setUp() called.\n";
        $this->includeDicon(TEST_DIR . "/s2container.php5/org/seasar/extension/unit/phpunit/test1.dicon");
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

    function tearDownTest1(){
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