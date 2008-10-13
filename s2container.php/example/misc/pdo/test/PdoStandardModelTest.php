<?php
class PdoStandardModelTest extends ::PHPUnit_Framework_TestCase {

    public function testCall() {
        $model = new PdoStandardModel();
        
        $model->FOO_BAR = 100;
        $this->assertEquals(100, $model->getFooBar());

        $model->setFooBar(200);
        $this->assertEquals(200, $model->getFooBar());

        $model = new PdoStandardModel();
        $model->foo_bar = 100;
        $this->assertEquals(100, $model->getFooBar());

        $model->setFooBar(200);
        $this->assertEquals(200, $model->getFooBar());

        $model = new PdoStandardModel();
        $model->Foo_Bar = 100;
        $this->assertEquals(100, $model->getFooBar());

        $model->setFooBar(200);
        $this->assertEquals(200, $model->getFooBar());

        $model = new PdoStandardModel();
        try {
            $model->getFooBar();
            $this->fail();
        } catch(Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        $model = new PdoStandardModel();
        try {
            $model->fooBar();
            $this->fail();
        } catch(Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        $model->FOOBAR = 100;
        $this->assertEquals(100, $model->getFoobar());

        $model->setFoobar(200);
        $this->assertEquals(200, $model->getFoobar());
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

