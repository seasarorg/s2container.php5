<?php
class Foo_S2ContainerApplicationContext {
    public function say(){
        return "i am foo.";
    }

    /**
     * @Binding('bar')
     */
    public function setBar($bar){
        $this->bar = $bar;
    }
}
?>
