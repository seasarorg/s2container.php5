<?php
class AbstractComponentAutoRegisterTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractComponentAutoRegisterTests();
        $this->assertIsA($register,'S2Container_AbstractAutoRegister');
        print "\n";
    }

    function testRegister() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractComponentAutoRegisterTests();
        $register->setContainer(new S2ContainerImpl());
        $classFilePath = dirname(__FILE__) . 
                         DIRECTORY_SEPARATOR .
                         'sample1' .
                         DIRECTORY_SEPARATOR .
                         'A_AbstractComponentAutoRegister.class.php';

        $register->register($classFilePath,
                            'A_AbstractComponentAutoRegister');
        $cd = $register->findComponentDef('a');
        $cd->init();
        $a = $cd->getComponent();
        $this->assertIsA($a,'S2Container_DefaultAopProxy');
        $this->assertEqual($a->testTrace(2,3),5);
        $this->assertEqual($a->getData(),1000);
        
        print "\n";
    }

    function testProcessClass() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractComponentAutoRegisterTests();
        $register->setContainer(new S2ContainerImpl());
        $classFilePath = dirname(__FILE__) . 
                         DIRECTORY_SEPARATOR .
                         'sample1' .
                         DIRECTORY_SEPARATOR .
                         'A_AbstractComponentAutoRegister.class.php';

        $cp = new S2Container_ClassPattern();
        $cp->setShortClassNames("^A_");
        $register->addClassPattern($cp);
        
        $register->processClass($classFilePath,
                            'A_AbstractComponentAutoRegister');
        $cd = $register->findComponentDef('a');
        $a = $cd->getComponent();
        $this->assertIsA($a,'S2Container_DefaultAopProxy');

        $classFilePath = dirname(__FILE__) . 
                         DIRECTORY_SEPARATOR .
                         'sample1' .
                         DIRECTORY_SEPARATOR .
                         'B_AbstractComponentAutoRegister.class.php';
        $register->processClass($classFilePath,'b');
        $cd = $register->findComponentDef('b');
        $this->assertEqual($cd,null);        
        
        print "\n";
    }

    function testProcessClassIgnore() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractComponentAutoRegisterTests();
        $register->setContainer(new S2ContainerImpl());
        $classFilePath = dirname(__FILE__) . 
                         DIRECTORY_SEPARATOR .
                         'sample1' .
                         DIRECTORY_SEPARATOR .
                         'A_AbstractComponentAutoRegister.class.php';

        $cp = new S2Container_ClassPattern();
        $cp->setShortClassNames("^A_");
        $register->addIgnoreClassPattern($cp);
        $register->addClassPattern($cp);
        
        $register->processClass($classFilePath,
                            'A_AbstractComponentAutoRegister');
        $cd = $register->findComponentDef('a');
        $this->assertEqual($cd,null);

        print "\n";
    }
}

class Test_AbstractComponentAutoRegisterTests
    extends S2Container_AbstractComponentAutoRegister{

    public function registerAll(){}
    
    public function __construct(){
        parent::__construct();   
    }        
    
    public function register($classFilePath, $className) {
        return parent::register($classFilePath, $className);
    }
    
    public function findComponentDef($name){
        return parent::findComponentDef($name);
    }
    
    public function addClassPattern($classPattern) {
        parent::addClassPatternInternal($classPattern);
    }

    public function addIgnoreClassPattern($classPattern) {
        parent::addIgnoreClassPatternInternal($classPattern);
    }    
}
?>
