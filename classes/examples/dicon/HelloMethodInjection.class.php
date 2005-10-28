<?php
class HelloMethodInjection implements Hello {

	private $buf = "";
	
	public function HelloMethodInjection() {
	}
	
	public function addMessage($message) {
		$this->buf .= $message;
	}

	public function showMessage() {
		print $this->buf . "\n";
	}
}
?>