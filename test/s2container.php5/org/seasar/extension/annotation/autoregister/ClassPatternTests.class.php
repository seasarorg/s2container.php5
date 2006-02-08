<?php
class ClassPatternTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testIsAppliedShortClassName() {

        print __METHOD__ . "\n";

        $pat = new S2Container_ClassPattern();
        $pat->setDirectoryPath('d:/tmp');
        $pat->setShortClassNames('Dao,Service');
        $this->assertTrue($pat->isAppliedShortClassName('FooDao'));

        $pat = new S2Container_ClassPattern();
        $pat->setDirectoryPath('d:/tmp');
        $this->assertTrue($pat->isAppliedShortClassName('FooDao'));

        print "\n";
    }

    function testGetDirectoryPath() {

        print __METHOD__ . "\n";

        $pat = new S2Container_ClassPattern();
        $pat->setDirectoryPath('d:/tmp');
        $pat->setShortClassNames('Dao,Service');
        $this->assertEqual($pat->getDirectoryPath(),'d:/tmp');

        print "\n";
    }
}
?>
