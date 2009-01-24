<?php
namespace sample\bbb;
class Hoge_S2ApplicationContext {

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
