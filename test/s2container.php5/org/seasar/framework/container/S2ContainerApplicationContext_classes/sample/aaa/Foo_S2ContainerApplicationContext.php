<?php
/**
 * @S2Meta('name' => 'xyz',
 *         'year' => '2007',
 *         'add'  => '2 + 3')
 */
class Foo_S2ContainerApplicationContext {
    public function say(){
        return "i am foo.";
    }

    public function setBar($bar){
        $this->bar = $bar;
    }
}
?>
