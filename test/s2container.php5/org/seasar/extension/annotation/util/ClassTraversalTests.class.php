<?php
class ClassTraversalTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {

        print __METHOD__ . "\n";

        $handler = new Handler_ClassTraversalTests();
        S2Container_ClassTraversal::forEachTime(dirname(__FILE__),
                                                $handler);
        $classMap = $handler->getClassMap();

        $this->assertTrue(isset($classMap[get_class($this)]));

        print "\n";
    }
}

class Handler_ClassTraversalTests 
    implements S2Container_ClassTraversalClassHandler {

    private $classMap = array();
    
    public function getClassMap(){
        return $this->classMap;    
    }
    
    public function processClass($classFilePath, $shortClassName){
        $this->classMap[$shortClassName] = $classFilePath;
    }
}
?>
