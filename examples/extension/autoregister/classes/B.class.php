<?php
/**
 * @S2Container_ComponentAnnotation(
 *              name = bbB
 *              )
 */
class B {
    
    /**
     * @S2Container_InitMethodAnnotation
     */
    function hello(){
        print __METHOD__ . " called.\n";   
    }   
    
    /**
     * @S2Container_BindingAnnotation(a)
     */
    function setA($a){
        $this->a = $a;
    }

    function setC(IC $c){
        $this->c = $c;
    }
    
    function calc($a,$b){
        print $this->c->calc($a,$b) . " result \n";
    }
}
?>
