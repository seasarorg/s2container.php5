<?php
class AbstractAutoNamingTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $naming = new AutoNaming_AbstractAutoNamingTests();
        $this->assertTrue($naming instanceof S2Container_AutoNaming);
        $this->assertIsA($naming,'S2Container_AbstractAutoNaming');

        $this->assertEqual(count($naming->getReplaceRules()),2);

        print "\n";
    }

    function testSetCustomizedName() {
        print __METHOD__ . "\n";

        $naming = new AutoNaming_AbstractAutoNamingTests();
        $naming->setCustomizedName("Emp","Employ");
        $this->assertEqual($naming->defineName("","Emp"),
                          "Employ");

        $naming->clearReplaceRule();
        $this->assertEqual(count($naming->getCustomizedNames()),0);
        $this->assertEqual(count($naming->getReplaceRules()),0);
        
        print "\n";
    }
}

class AutoNaming_AbstractAutoNamingTests 
    extends S2Container_AbstractAutoNaming{
    
    public function getCustomizedNames(){
        return $this->customizedNames;
    }

    public function getReplaceRules(){
        return $this->replaceRules;
    }
    
    public function makeDefineName($directoryPath, $shortClassName) {
        return $shortClassName;
    }        
    function __construct(){
        parent::__construct();    
    }
    
}
?>
