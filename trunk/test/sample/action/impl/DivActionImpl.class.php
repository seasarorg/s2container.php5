<?php
 
class DivActionImpl implements IDivAction {
   
   private $logic;
   
   public function __construct(ICalculator $logic) {
       $this->logic = $logic;
   }
   
   public function div($a,$b) {
       return $this->logic->div($a,$b);
   }
}

?>
