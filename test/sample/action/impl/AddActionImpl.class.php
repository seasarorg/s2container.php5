<?php
 
class AddActionImpl implements IAddAction {
   
   private $logic;
   
   public function __construct(ICalculator $logic) {
       $this->logic = $logic;
   }
   
   public function add($a,$b) {
       return $this->logic->add($a,$b);
   }
}

?>
