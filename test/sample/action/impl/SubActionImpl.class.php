<?php
 
class SubActionImpl implements ISubAction {
   
   private $logic;
   
   public function setLogic(ICalculator $logic) {
       $this->logic = $logic;
   }
   
   public function sub($a,$b) {
       return $this->logic->sub($a,$b);
   }
}

?>
