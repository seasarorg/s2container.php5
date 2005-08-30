<?php
class ConstructerUtilTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testParent() {
       
        print __METHOD__ . "\n";
       
        $uRef = new ReflectionClass('U');
        $args = array(new D());
        $u = new U($args[0]);
        $uRef->newInstance(new D());
       
        print "\n";
    }

    function testInstance() {
       
        print __METHOD__ . "\n";
       
        $a = ConstructorUtil::newInstance(new ReflectionClass('A'),
                                         array());
        $this->assertIsA($a,'A');

        $a = ConstructorUtil::newInstance(new ReflectionClass('A'),
                                         null);
        $this->assertIsA($a,'A');
       
        print "\n";
    }

    function testInstanceWithArgs() {
       
        print __METHOD__ . "\n";
       
        $c = ConstructorUtil::newInstance(new ReflectionClass('C'),
                                         array('hoge'));
        $this->assertIsA($c,'C');
       
        print "\n";
    }

    function testInstanceWithAspect() {
       
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('A','a');
        $aspect1 = new AspectDefImpl(new PointcutImpl('A'),new TraceInterceptor());
        $cd->addAspectDef($aspect1);       
        $c = ConstructorUtil::newInstance(new ReflectionClass('A'),
                                         array(),$cd);
        $this->assertIsA($c,'UuCallAopProxyAEnhancedByS2AOP');
       
        print "\n";
    }
}
?>