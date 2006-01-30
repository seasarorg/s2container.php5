<?php
class ClassPatternTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {

        print __METHOD__ . "\n";

        $pat = new S2Container_ClassPattern('d:/tmp');
        $pat = new S2Container_ClassPattern('d:/tmp','Hoge,Foo');

        try{
            $pat = new S2Container_ClassPattern('z:/tmp','Hoge,Foo');
            $this->assertTrue(false);
        }catch(Exception $e){
            $this->assertTrue(true);
            print "{$e->getMessage()}\n";
        }

        print "\n";
    }

    function testIsAppliedShortClassName() {

        print __METHOD__ . "\n";

        $pat = new S2Container_ClassPattern('d:/tmp','Dao,Service');
        $this->assertTrue($pat->isAppliedShortClassName('FooDao'));

        $pat = new S2Container_ClassPattern('d:/tmp');
        $this->assertTrue($pat->isAppliedShortClassName('FooDao'));

        print "\n";
    }

    function testGetDirectoryPath() {

        print __METHOD__ . "\n";

        $pat = new S2Container_ClassPattern('d:/tmp','Dao,Service');
        $this->assertEqual($pat->getDirectoryPath(),'d:/tmp');

        print "\n";
    }
}
?>
