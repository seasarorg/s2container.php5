<?php
class AutoHelloConstructorInjection implements Hello {

	private $messages;
	
	public function AutoHelloConstructorInjection(Map $messages) {
		$this->messages = $messages;
	}
	
	public function showMessage() {
		print $this->messages->get('hello') . "\n";
	}
}
?>
