<?php
class ServiceB extends ServiceA {
    public function sub($a, $b) {
        return $b - $a;
    }
}