<?php
class PdoInterceptorTest extends \PHPUnit_Framework_TestCase {

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

