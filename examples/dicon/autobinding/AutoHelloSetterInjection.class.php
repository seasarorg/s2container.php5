<?php
class AutoHelloSetterInjection implements Hello {

	private $messages;
	
	public function AutoHelloSetterInjection() {
	}
	
	public function setMessage(Map $messages) {
		$this->messages = $messages;
	}

	public function showMessage() {
		print $this->messages->get('hello') . "\n";
	}
}
?>
