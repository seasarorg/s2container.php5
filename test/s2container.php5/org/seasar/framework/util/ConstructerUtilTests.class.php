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
       
        $a = S2Container_ConstructorUtil::newInstance(new ReflectionClass('A'),
                                         array());
        $this->assertIsA($a,'A');

        $a = S2Container_ConstructorUtil::newInstance(new ReflectionClass('A'),
                                         null);
        $this->assertIsA($a,'A');
       
        print "\n";
    }

    function testInstanceWithArgs() {
       
        print __METHOD__ . "\n";
       
        $c = S2Container_ConstructorUtil::newInstance(new ReflectionClass('C'),
                                         array('hoge'));
        $this->assertIsA($c,'C');
       
        print "\n";
    }

    function testIllegalRelfection() {
       
        print __METHOD__ . "\n";

        try{
            $c = S2Container_ConstructorUtil::newInstance('C',array('hoge'));
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_IllegalArgumentException');
            print "{$e->getMessage()}\n";
        }

        print "\n";
    }
}
?>