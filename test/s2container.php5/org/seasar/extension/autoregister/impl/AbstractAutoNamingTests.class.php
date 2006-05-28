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

        print "\n";
    }

    function testClearReplaceRule() {
        print __METHOD__ . "\n";
        $naming = new AutoNaming_AbstractAutoNamingTests();
        $naming->setCustomizedName("Emp","Employ");
        $this->assertEqual(count($naming->getCustomizedNames()),1);
        $this->assertEqual(count($naming->getReplaceRules()),2);
        $naming->clearReplaceRule();
        $this->assertEqual(count($naming->getCustomizedNames()),0);
        $this->assertEqual(count($naming->getReplaceRules()),0);
        
        print "\n";
    }

    function testApplyRule() {
        print __METHOD__ . "\n";
        $naming = new AutoNaming_AbstractAutoNamingTests();
        $this->assertEqual('hoge',$naming->testApplyRule("HogeBean"));
        $this->assertEqual('hoge',$naming->testApplyRule("HogeImpl"));
        $naming->clearReplaceRule();

        $naming->addReplaceRule('Bar','BAR');
        $this->assertEqual('fooBARFoo',$naming->testApplyRule("FooBarFoo"));
        $naming->clearReplaceRule();

        $naming->addReplaceRule('^Bar$','Bbar');
        $this->assertEqual('bbar',$naming->testApplyRule("Bar"));
        $this->assertEqual('xxx',$naming->testApplyRule("Xxx"));
        $naming->clearReplaceRule();
        
        print "\n";
    }

    function testDecapitalizeFalse() {
        print __METHOD__ . "\n";
        $naming = new AutoNaming_AbstractAutoNamingTests();
        $naming->setDecapitalize(false);        
        $this->assertEqual('Hoge',$naming->testApplyRule("HogeBean"));

        print "\n";
    }

}

class AutoNaming_AbstractAutoNamingTests 
    extends S2Container_AbstractAutoNaming{
    
    function __construct(){
        parent::__construct();    
    }

    public function testApplyRule($name){
        return parent::applyRule($name);
    }

    public function getCustomizedNames(){
        return $this->customizedNames;
    }

    public function getReplaceRules(){
        return $this->replaceRules;
    }
    
    public function makeDefineName($directoryPath, $shortClassName) {
        return $shortClassName;
    }        
    
    
}
?>
