<?php
/**
 * @Component('name' => 
 *            'hoge')
 */
class Hoge_S2ContainerApplicationContext {
    /**
     * @Aspect('interceptor' => 'new S2Container_TraceInterceptor()')
     */
    public function say(){
        return "i am hoge.";
    }

    private $bar;
    public function sayBar(){
        return $this->bar->say();
    }

    /**
     * @Binding('Bar')
     */
    public function setBar($bar){
        $this->bar = $bar;
    }
}
?>
