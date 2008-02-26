<?php
require_once(ROOT_DIR . '/classes/Paginate.php');
class PaginateTest extends ::PHPUnit_Framework_TestCase {

    public function testGetTotal() {
        $paginate = new Paginate;
        try {
            $paginate->getTotal();
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function testGetTotalPage() {
        $paginate = new Paginate;
        $paginate->setTotal(49);
        $this->assertEquals($paginate->getTotalPage(), 5);
        $paginate->setTotal(50);
        $this->assertEquals($paginate->getTotalPage(), 5);
        $paginate->setTotal(51);
        $this->assertEquals($paginate->getTotalPage(), 6);
    }

    public function testSetPage() {
        $paginate = new Paginate;
        $paginate->setTotal(55);
        try {
            $paginate->setPage(8);
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }

        $paginate->setPage(5);
        $this->assertEquals($paginate->getPage(), 5);
        $this->assertEquals($paginate->getOffset(), 40);

        $paginate->setPage(2);
        $this->assertEquals($paginate->getPage(), 2);
        $this->assertEquals($paginate->getOffset(), 10);
    }

    public function testNext() {
        $paginate = new Paginate;
        $paginate->setTotal(55);
        $paginate->next();
        $this->assertEquals($paginate->getPage(), 2);
        $this->assertEquals($paginate->getOffset(), 10);

        $paginate->setPage(6);
        $paginate->next();
        $this->assertEquals($paginate->getPage(), 6);
        $this->assertEquals($paginate->getOffset(), 50);

    }

    public function testIsNext() {
        $paginate = new Paginate;
        $paginate->setTotal(55);

        $this->assertTrue($paginate->isNext());

        $paginate->setPage(6);
        $this->assertFalse($paginate->isNext());
    }

    public function testPrev() {
        $paginate = new Paginate;
        $paginate->setTotal(55);

        $paginate->setPage(6);
        $paginate->prev();
        $this->assertEquals($paginate->getPage(), 5);
        $this->assertEquals($paginate->getOffset(), 40);

        $paginate->setPage(1);
        $paginate->prev();
        $this->assertEquals($paginate->getPage(), 1);
        $this->assertEquals($paginate->getOffset(), 0);
    }

    public function testIsPrev() {
        $paginate = new Paginate;
        $paginate->setTotal(55);

        $paginate->setPage(6);
        $this->assertTrue($paginate->isPrev());

        $paginate->setPage(1);
        $this->assertFalse($paginate->isPrev());
    }

    public function testPages() {
        $paginate = new Paginate;
        $paginate->setTotal(55);
        $this->assertEquals($paginate->pages(), array(1,2,3));

        $paginate->setPage(3);
        $this->assertEquals($paginate->pages(), array(1,2,3,4,5));

        $paginate->setPage(4);
        $this->assertEquals($paginate->pages(), array(2,3,4,5,6));

        $paginate->setPage(5);
        $this->assertEquals($paginate->pages(), array(3,4,5,6));

        $paginate->setPage(6);
        $this->assertEquals($paginate->pages(), array(4,5,6));

        $paginate->setWindow(10);
        $this->assertEquals($paginate->pages(), array(2,3,4,5,6));

        $paginate->setPage(5);
        $this->assertEquals($paginate->pages(), array(1,2,3,4,5,6));

        $paginate->setPage(4);
        $this->assertEquals($paginate->pages(), array(1,2,3,4,5,6));

        $paginate->setPage(1);
        $this->assertEquals($paginate->pages(), array(1,2,3,4,5,6));
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}
