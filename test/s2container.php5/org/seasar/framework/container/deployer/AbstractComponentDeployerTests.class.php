<?php
class AbstractComponentDeployerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testSetupAssemblerForAuto() {
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_AUTO);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'AutoConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'AutoPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'DefaultDestroyMethodAssembler');

        $cd = new ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_AUTO);
        $cd->setExpression("d");        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'ExpressionConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'AutoPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'DefaultDestroyMethodAssembler');

        $cd = new ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_AUTO);
        $cd->addArgDef(new ArgDefImpl('test'));
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'ManualConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'AutoPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'DefaultDestroyMethodAssembler');
                         
        print "\n";
    }

    function testSetupAssemblerForConstructor() {
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'AutoConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'ManualPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'DefaultDestroyMethodAssembler');

        print "\n";
    }

    function testSetupAssemblerForProperty() {
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_PROPERTY);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'ManualConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'AutoPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'DefaultDestroyMethodAssembler');

        print "\n";
    }

    function testSetupAssemblerForNone() {
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_NONE);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'DefaultConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'DefaultPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'DefaultDestroyMethodAssembler');

        $cd = new ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(ContainerConstants::AUTO_BINDING_NONE);
        $cd->addArgDef(new ArgDefImpl('test'));
        $cd->addPropertyDef(new PropertyDefImpl('test','test'));
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'ManualConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'ManualPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'DefaultDestroyMethodAssembler');

        print "\n";
    }
}

class TestComponentDeployer extends AbstractComponentDeployer{
    public function TestComponentDeployer(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }	

    public function getConstructorAssemblerTest(){
    	return $this->getConstructorAssembler();
    }

    public function getPropertyAssemblerTest(){
    	return $this->getPropertyAssembler();
    }

    public function getInitMethodAssemblerTest(){
    	return $this->getInitMethodAssembler();
    }

    public function getDestroyMethodAssemblerTest(){
    	return $this->getDestroyMethodAssembler();
    }
    
    public function deploy(){}
    
    public function injectDependency($outerComponent){}
    
    public function init(){}
    
    public function destroy(){}
}
?>