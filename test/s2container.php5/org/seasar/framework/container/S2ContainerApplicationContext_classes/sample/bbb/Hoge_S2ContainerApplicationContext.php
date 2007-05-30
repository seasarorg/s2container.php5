<?php
class Hoge_S2ContainerApplicationContext {

    public function say(){
        return "i am hoge.";
    }

    private $bar;
    public function sayBar(){
        return $this->bar->say();
    }

    public function setBar($bar){
        $this->bar = $bar;
    }
}
?>
