<?php
class Service {
    public $dao = 's2binding';
    public function execute() {
        foreach (get_declared_interfaces() as $i) {
            if (preg_match('/^seasar::/', $i)) {
                print "'$i'," . PHP_EOL;
            }
        }
        foreach (get_declared_classes() as $i) {
            if (!preg_match('/^seasar::/', $i) or
                preg_match('/Config/', $i) or
                preg_match('/ClassLoader/', $i) or
                preg_match('/TraceInterceptor/', $i)
                ) {
                continue;
            }
            print "'$i'," . PHP_EOL;
        }
        var_dump($this->dao);
    }
}