<?php
class Checker {
	public function check($str) {
		if ($str != null) {
			print $str . "\n";
		} else {
			throw new Exception("null");
		}
	}
}
?>