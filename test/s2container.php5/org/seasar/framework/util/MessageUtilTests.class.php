<?php
class MessageUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testMessageUtil() {
       
        print __METHOD__ . "\n";
       
        $msg = S2ContainerMessageUtil::getMessageWithArgs("ESSR0001",array('java file'));
        $this->assertEqual($msg,"java file not found");

        $msg = S2ContainerMessageUtil::getMessageWithArgs("ESSR0003",array('string','int'));
        $this->assertEqual($msg,"<string> unexpected:<int>");

        print "\n";
    }
}
?>