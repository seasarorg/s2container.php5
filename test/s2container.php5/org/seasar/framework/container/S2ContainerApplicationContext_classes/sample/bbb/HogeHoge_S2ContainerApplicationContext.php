<?php
/**
 * @S2Component('name' => 'hoge',
 *            'available' => false)
 */
class HogeHoge_S2ContainerApplicationContext {
    /**
     * @S2Aspect('interceptor' => 'new S2Container_TraceInterceptor()')
     */
    public function say(){
        return "i am hoge.";
    }

    private $bar;
    public function sayBar(){
        return $this->bar->say();
    }

    /**
     * @S2Binding('Bar')
     */
    public function setBar($bar){
        $this->bar = $bar;
    }
}
?>
