<?php
class DefaultAutoNamingTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $naming = new S2Container_DefaultAutoNaming();
        $this->assertIsA($naming,'S2Container_DefaultAutoNaming');

        print "\n";
    }

    function testMakeDefineName() {
        print __METHOD__ . "\n";

        $naming = new S2Container_DefaultAutoNaming();
        $naming->addReplaceRule("Test$","TEST");
        $val = $naming->makeDefineName("",'ZZzTest');
        $this->assertEqual($val,"zZzTEST");

        $naming->setDecapitalize(false);
        $val = $naming->makeDefineName("",'ZZzTestXxx');
        $this->assertEqual($val,"ZZzTestXxx");

        print "\n";
    }

}
?>
