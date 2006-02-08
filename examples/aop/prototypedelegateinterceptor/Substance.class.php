<?php
class Substance implements IBase{
	private $sum = 0;
	
	public function run() {
		print "sum : " . $this->sum . "\n";
		$this->sum++;
	}
	
}
?>
