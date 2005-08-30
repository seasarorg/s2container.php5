<?php
class ExpressionConstructorAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testExp() {
       
        print __METHOD__ . "\n";
       
        $cd = new ComponentDefImpl('','dd');
        $cd->setExpression("return new D();");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
          
        $d = $container->getComponent('dd');
        $this->assertIsA($d,'D');

        print "\n";
    }   

    function testGetComponent() {
       
        print __METHOD__ . "\n";
       
        $cd = new ComponentDefImpl('','dd');
        $cd->setExpression("d");
        
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register($cd);
          
        $d = $container->getComponent('d');
        $this->assertIsA($d,'D');

        $dd = $container->getComponent('dd');
        $this->assertIsA($dd,'D');

        $this->assertReference($d,$dd);

        print "\n";
    }   
    
    function testNotObjectException() {
       
        print __METHOD__ . "\n";
       
        $cd = new ComponentDefImpl('','dd');
        $cd->setExpression("return 100;");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
     
        try{
            $dd = $container->getComponent('dd');
        }catch(Exception $e ){
            $this->assertIsA($e,'S2RuntimeException');
            print $e->getMessage() ."\n";
        }

        print "\n";
    }       
    
    function testClassUnmatchException() {
       
        print __METHOD__ . "\n";
       
        $cd = new ComponentDefImpl('C','dd');
        $cd->setExpression("return new D();");
        
        $container = new S2ContainerImpl();
        $container->register($cd);
     
        try{
            $dd = $container->getComponent('dd');
        }catch(Exception $e ){
            $this->assertIsA($e,'ClassUnmatchRuntimeException');
            print $e->getMessage() ."\n";
        }

        print "\n";
    }           
}
?>