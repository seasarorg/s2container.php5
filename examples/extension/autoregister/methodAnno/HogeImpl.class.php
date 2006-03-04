<?
class HogeImpl {
    const INIT_METHOD = 'initA,initB';

    /**
     * @S2Container_InitMethodAnnotation
     */
    public function initA(){
        print __METHOD__ . " called.\n";
    }

    /**
     * @S2Container_InitMethodAnnotation
     */
    public function initB(){
        print __METHOD__ . " called.\n";
    }
}
?>
