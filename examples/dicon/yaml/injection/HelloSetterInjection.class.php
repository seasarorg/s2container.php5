<?php
class HelloSetterInjection implements Hello {

	private $message;
	
	public function HelloSetterInjection() {
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}

	public function showMessage() {
		print $this->message . "\n";
	}
}
?>
