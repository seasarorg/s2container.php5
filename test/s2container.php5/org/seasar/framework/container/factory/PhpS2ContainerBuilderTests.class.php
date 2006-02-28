<?php
class PhpS2ContainerBuilderTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testArgValue() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test1.php');
        $this->assertNotNull($container);
        $this->assertIsA($container,'S2ContainerImpl');
  
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A_PhpS2ContainerBuilderTests');
        $this->assertEqual($a->getValue(),'value b');
  
        print "\n";
    }

    function testArgRef() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test2.php');
        $this->assertNotNull($container);
        $this->assertIsA($container,'S2ContainerImpl');
  
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A_PhpS2ContainerBuilderTests');
        $this->assertIsA($a->getValue(),'B_PhpS2ContainerBuilderTests');
 
        print "\n";
    }

    function testPropExp() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test2.php');
        $this->assertNotNull($container);
        $this->assertIsA($container,'S2ContainerImpl');
  
        $b = $container->getComponent('b');
        $this->assertIsA($b,'B_PhpS2ContainerBuilderTests');
        $this->assertEqual($b->getValue(),2);
  
        print "\n";
    }

    function testMethod() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test3.php');
        $this->assertNotNull($container);
        $this->assertIsA($container,'S2ContainerImpl');
  
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A_PhpS2ContainerBuilderTests');
  
        $container->init();
        $container->destroy();
  
        print "\n";
    }

    function testMethodArg() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test4.php');
        $this->assertNotNull($container);
        $this->assertIsA($container,'S2ContainerImpl');
  
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A_PhpS2ContainerBuilderTests');
  
        $container->init();
        $container->destroy();
  
        print "\n";
    }

    function testAspect() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test5.php');
        $this->assertNotNull($container);
        $this->assertIsA($container,'S2ContainerImpl');
  
        $b = $container->getComponent('b');
        $this->assertEqual($b->getValue(),2);
        $this->assertEqual($b->test(2,3),5);
   
        print "\n";
    }

    function testInclude() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test6.php');
        $this->assertNotNull($container);
        $this->assertIsA($container,'S2ContainerImpl');
  
        $a = $container->getComponent('a');
        $b = $a->getValue();
        $this->assertIsA($b,'B_PhpS2ContainerBuilderTests');
   
        print "\n";
    }

    function testIncludeException() {
       
        print __METHOD__ . "\n";

        try{
            $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test8.php');
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }
   
        print "\n";
    }

    function testAutoBinding() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test9.php');
        $c = $container->getComponent('c');
        $this->assertTrue($c->getDObj() instanceof ID_PhpS2ContainerBuilderTests );
         
        print "\n";
    }
}

class A_PhpS2ContainerBuilderTests{
    private $value;
    
    public function __construct($value){
        $this->value = $value;
    }   

    public function getValue(){
        return $this->value;   
    }

    public function initTest(){
        print __METHOD__ . " called.\n";   
    }

    public function initTest2($value){
        print __METHOD__ . " value : $value \n";   
    }

    public function destroyTest(){
        print __METHOD__ . " called.\n";   
    }

    public function destroyTest2($value){
        $value = get_class($value);
        print __METHOD__ . " value = $value \n";   
    }
}

class B_PhpS2ContainerBuilderTests{
    private $value;
    
    public function __construct(){}

    public function setValue($value){
        $this->value = $value;   
    }
    
    public function getValue(){
        return $this->value;   
    }

    public function test($a,$b){
        return $a + $b;
    }
}

class C_PhpS2ContainerBuilderTests{
    private $dobj;
    
    public function __construct(){}

    public function setDobj(ID_PhpS2ContainerBuilderTests $dobj){
        $this->dobj = $dobj;   
    }
    
    public function getDobj(){
        return $this->dobj;   
    }
}

interface ID_PhpS2ContainerBuilderTests{}
class D_PhpS2ContainerBuilderTests
    implements ID_PhpS2ContainerBuilderTests {
}

?>