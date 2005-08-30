<?php
class AutoHelloConstructorInjection2 implements Hello {

	private $messages;
	private $value;
/*	
	public function AutoHelloConstructorInjection2(
	        Map $messages,$value) {
		$this->messages = $messages;
		$this->value = $value;
	}
*/
/*	
	public function AutoHelloConstructorInjection2(
	        Map $messages,$value=100) {
		$this->messages = $messages;
		$this->value = $value;
	}
	

*/
/*
	public function AutoHelloConstructorInjection2(
	        $value,Map $messages) {
		$this->messages = $messages;
		$this->value = $value;
	}
	

*/	
	public function AutoHelloConstructorInjection2(
	        $value=100,Map $messages) {
		$this->messages = $messages;
		$this->value = $value;
	}
	
	public function showMessage() {
		print $this->messages->get('hello') . " : " . $this->value ." - \n";
	}
}
?>
