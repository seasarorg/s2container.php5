<?php
class PdoInterceptorTest extends \PHPUnit_Framework_TestCase {

    public function testGetQueryFromSqlFile() {
        $refClass = new \ReflectionClass('B_PdoInterceptor');
        $interceptor = new PdoInterceptor;
        $sql = $interceptor->getQueryFromSqlFile($refClass, $refClass->getMethod('a'), array());
        $this->assertEquals(trim($sql), 'select * from CD;');
        $sql = $interceptor->getQueryFromSqlFile($refClass, $refClass->getMethod('b'), array('zzz' => 1000, 'xxx' => 2000));
        $this->assertEquals(trim($sql), 'select * from CD where zzz = 1000 and xxx = 2000;');
    }

    public function testResolveValue() {
        $obj = new C_PdoInterceptor;
        $interceptor = new PdoInterceptor;
        $value = $interceptor->resolveValue($obj, array('a'));  // obj->a
        $this->assertEquals($value, 1);
        $value = $interceptor->resolveValue($obj, array('b'));  // obj->b()
        $this->assertEquals($value, 2);
        $value = $interceptor->resolveValue($obj, array('c', 0));  // obj->c[0]
        $this->assertEquals($value, 3);
        $value = $interceptor->resolveValue($obj, array('d', 0, 0));  // obj->d()[0][0]
        $this->assertEquals($value, 4);
        $value = $interceptor->resolveValue($obj, array('e', 0, 0, 'd', 0, 0));  // obj->e()[0][0]->d()[0][0]
        $this->assertEquals($value, 4);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_PdoInterceptor {
    public function findA() {}
    public function findB() {}
    public function findC() {}
}

class B_PdoInterceptor {
    public function a(){}
    public function b(){}
}

class C_PdoInterceptor {
    public $a = 1;
    public function b() {
        return 2;
    }
    public $c = array(3);
    public function d() {
        return array(array(4));
    }

    public function e() {
        return array(array(new C_PdoInterceptor));
    }
}

