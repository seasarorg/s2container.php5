<?php
class AbstractComponentDeployerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testSetupAssemblerForAuto() {
        print __METHOD__ . "\n";

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'S2Container_AutoConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'S2Container_AutoPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'S2Container_DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'S2Container_DefaultDestroyMethodAssembler');

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        $cd->setExpression("d");        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'S2Container_ExpressionConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'S2Container_AutoPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'S2Container_DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'S2Container_DefaultDestroyMethodAssembler');

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_AUTO);
        $cd->addArgDef(new S2Container_ArgDefImpl('test'));
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'S2Container_ManualConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'S2Container_AutoPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'S2Container_DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'S2Container_DefaultDestroyMethodAssembler');
                         
        print "\n";
    }

    function testSetupAssemblerForConstructor() {
        print __METHOD__ . "\n";

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'S2Container_AutoConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'S2Container_ManualPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'S2Container_DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'S2Container_DefaultDestroyMethodAssembler');

        print "\n";
    }

    function testSetupAssemblerForProperty() {
        print __METHOD__ . "\n";

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_PROPERTY);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'S2Container_ManualConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'S2Container_AutoPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'S2Container_DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'S2Container_DefaultDestroyMethodAssembler');

        print "\n";
    }

    function testSetupAssemblerForNone() {
        print __METHOD__ . "\n";

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_NONE);
        
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'S2Container_DefaultConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'S2Container_DefaultPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'S2Container_DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'S2Container_DefaultDestroyMethodAssembler');

        $cd = new S2Container_ComponentDefImpl('C','c');
        $cd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_NONE);
        $cd->addArgDef(new S2Container_ArgDefImpl('test'));
        $cd->addPropertyDef(new S2Container_PropertyDefImpl('test','test'));
        $deployer = new TestComponentDeployer($cd);  

        $this->assertIsA($deployer->getConstructorAssemblerTest(),
                         'S2Container_ManualConstructorAssembler');
        $this->assertIsA($deployer->getPropertyAssemblerTest(),
                         'S2Container_ManualPropertyAssembler');
        $this->assertIsA($deployer->getInitMethodAssemblerTest(),
                         'S2Container_DefaultInitMethodAssembler');
        $this->assertIsA($deployer->getDestroyMethodAssemblerTest(),
                         'S2Container_DefaultDestroyMethodAssembler');

        print "\n";
    }
}

class TestComponentDeployer extends S2Container_AbstractComponentDeployer{
    public function TestComponentDeployer(S2Container_ComponentDef $componentDef) {
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