<?php
class ArgExp {

	private $value;
	private $message = "b";
	
	public function ArgExp($value=-1) {
		$this->value = $value;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}

	public function showValue() {
		print $this->value . "\n";
	}

	public function showMessage() {
		print $this->message . "\n";
	}
}
?>
