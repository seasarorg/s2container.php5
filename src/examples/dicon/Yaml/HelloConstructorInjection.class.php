<?php
class HelloConstructorInjection implements Hello {

	private $message;
	
	public function HelloConstructorInjection($message) {
		$this->message = $message;
	}
	
	public function showMessage() {
		print $this->message . "\n";
	}
}
?>
