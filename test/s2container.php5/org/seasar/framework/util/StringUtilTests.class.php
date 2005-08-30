<?php
class StringUtilTests extends UnitTestCase {
   function __construct() {
       $this->UnitTestCase();
   }

   function testExpandPath() {
       
       print __METHOD__ . "\n";
       
       $path = "%SRC_DIR%/test/test.txt";
       $path = StringUtil::expandPath($path);
 
       $this->assertEqual($path,SRC_DIR . "/test/test.txt");
       
       print "\n";
   }
}
?>