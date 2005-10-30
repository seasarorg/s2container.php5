<?php
class ContainerAllTest {

    public static function main(){
        if (TextReporter::inCli()) {  
            print "\n\n============================\n";
            exit (self::group()->run(new TextReporter()) ? 0 : 1);
        }
        self::group()->run(new HtmlReporter()); 	
    }
    
    public static function group() {
        $group = new GroupTest('');

        $group->addTestCase(new AutoConstructorAssemblerTests());
        $group->addTestCase(new AutoPropertyAssemblerTests());
        $group->addTestCase(new DefaultDestroyMethodAssemblerTests());
        $group->addTestCase(new DefaultInitMethodAssemblerTests());
        $group->addTestCase(new ExpressionConstructorAssemblerTests());
        $group->addTestCase(new ManualConstructorAssemblerTests());
        $group->addTestCase(new ManualPropertyAssemblerTests());
        $group->addTestCase(new AbstractAssemblerTests());
        $group->addTestCase(new AbstractConstructorAssemblerTests());

        $group->addTestCase(new AbstractComponentDeployerTests());
        $group->addTestCase(new OuterComponentDeployerTests());
        $group->addTestCase(new PrototypeComponentDeployerTests());
        $group->addTestCase(new RequestComponentDeployerTests());
        $group->addTestCase(new SessionComponentDeployerTests());

        $group->addTestCase(new XmlS2ContainerBuilderTests());
        $group->addTestCase(new IniS2ContainerBuilderTests());
        $group->addTestCase(new S2ContainerFactoryTests());
        $group->addTestCase(new SingletonS2ContainerFactoryTests());

        $group->addTestCase(new S2ContainerImplTests());
        $group->addTestCase(new ArgDefImplTests());
        $group->addTestCase(new AspectDefImplTests());
        $group->addTestCase(new ComponentDefImplTests());
        $group->addTestCase(new MethodDefImplTests());

        $group->addTestCase(new AopProxyUtilTests());
        $group->addTestCase(new InstanceModeUtilTests());
        $group->addTestCase(new AutoBindingUtilTests());
        $group->addTestCase(new S2ContainerClassLoaderTests());

        return $group;    	
    }
}
?>