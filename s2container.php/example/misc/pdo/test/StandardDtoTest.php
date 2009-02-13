<?php
class StandardDtoTest extends \PHPUnit_Framework_TestCase {

    public function testCall1() {
        $dto = new StandardDto();
        
        $dto->FOO_BAR = 100;
        $this->assertEquals(100, $dto->getFooBar());

        $dto->setFooBar(200);
        $this->assertEquals(200, $dto->getFooBar());

        $dto = new StandardDto();
        $dto->foo_bar = 100;
        $this->assertEquals(100, $dto->getFooBar());

        $dto->setFooBar(200);
        $this->assertEquals(200, $dto->getFooBar());

        $dto = new StandardDto();
        $dto->Foo_Bar = 100;
        $this->assertEquals(100, $dto->getFooBar());

        $dto->setFooBar(200);
        $this->assertEquals(200, $dto->getFooBar());

        $dto = new StandardDto();
        try {
            $dto->getFooBar();
            $this->fail();
        } catch(Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        $dto = new StandardDto();
        try {
            $dto->fooBar();
            $this->fail();
        } catch(Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        $dto->FOOBAR = 100;
        $this->assertEquals(100, $dto->getFoobar());

        $dto->setFoobar(200);
        $this->assertEquals(200, $dto->getFoobar());

        $dto = new StandardDto();
        $dto->abc = 100;
        $this->assertEquals(100, $dto->getabc());
        $dto->abc_Def = 100;
        $this->assertEquals(100, $dto->getabcDef());
    }

    public function testCall2() {
        $dto = new StandardDto();
        $dto->FOO_BAR = 100;
        $dto->setFooBar(200);
        $this->assertEquals(200, $dto->FOO_BAR);
        $this->assertEquals(200, $dto->getFooBar());

        $dto = new StandardDto();
        $dto->foo_bar = 100;
        $dto->setFooBar(200);
        $this->assertEquals(200, $dto->foo_bar);
        $this->assertEquals(200, $dto->getFooBar());

        $dto = new StandardDto();
        $dto->Foo_Bar = 100;
        $dto->setFooBar(200);
        $this->assertEquals(200, $dto->Foo_Bar);
        $this->assertEquals(200, $dto->getFooBar());
    }

    public function testCall3() {
        $dto = new StandardDto();
        $dto->FOO_BAR = 100;
        $dto->setfooBar(200);
        $this->assertEquals(200, $dto->FOO_BAR);
        $this->assertEquals(200, $dto->getfooBar());

        $dto = new StandardDto();
        $dto->foo_bar = 100;
        $dto->setfooBar(200);
        $this->assertEquals(200, $dto->foo_bar);
        $this->assertEquals(200, $dto->getfooBar());

        $dto = new StandardDto();
        $dto->foo_Bar = 100;
        $dto->setfooBar(200);
        $this->assertEquals(200, $dto->foo_Bar);
        $this->assertEquals(200, $dto->getfooBar());
    }

    public function testCall4() {
        $dto = new StandardDto();
        $dto->FOO = 100;
        $dto->setFoo(200);
        $this->assertEquals(200, $dto->FOO);
        $this->assertEquals(200, $dto->getFoo());

        $dto = new StandardDto();
        $dto->foo = 100;
        $dto->setFoo(200);
        $this->assertEquals(200, $dto->foo);
        $this->assertEquals(200, $dto->getFoo());

        $dto = new StandardDto();
        $dto->Foo = 100;
        $dto->setFoo(200);
        $this->assertEquals(200, $dto->Foo);
        $this->assertEquals(200, $dto->getFoo());
    }

    public function testCall5() {
        $dto = new StandardDto();
        $dto->FOO = 100;
        $dto->setfoo(200);
        $this->assertEquals(200, $dto->FOO);
        $this->assertEquals(200, $dto->getfoo());

        $dto = new StandardDto();
        $dto->foo = 100;
        $dto->setfoo(200);
        $this->assertEquals(200, $dto->foo);
        $this->assertEquals(200, $dto->getfoo());
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

