<?php
class OuterComponentDeployerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testOuter1() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d');
        $container->register('L','l');
          
        $cd = $container->getComponentDef('l');
        $cd->setInstanceMode(ContainerConstants::INSTANCE_OUTER);
          
        $l = new L();
        $this->assertNull($l->getComp());

        $container->injectDependency($l);
        $this->assertIsA($l->getComp(),'D');
          
        print "\n";
    }

    function testCheckComponentClass() {
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('L','l');
        $cd->setInstanceMode(ContainerConstants::INSTANCE_OUTER);
        
        $deployer = new OuterComponentDeployer($cd);  

        try{
            $deployer->injectDependency(new A());
        }catch(Exception $e){
        	$this->assertIsA($e,'ClassUnmatchRuntimeException');
        	print $e->getMessage() . "\n";
        }    

        print "\n";
    }
}
?>