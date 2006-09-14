<?php
class ComponentExp {

	private $messageA;
	private $messageB = "b";
	
	public function ComponentExp($messageA = "a") {
		$this->messageA = $messageA;
	}
	
	public function setMessageB($messageB) {
		$this->messageB = $messageB;
	}

	public function showMessageA() {
		print $this->messageA . "\n";
	}

	public function showMessageB() {
		print $this->messageB . "\n";
	}
}
?>
