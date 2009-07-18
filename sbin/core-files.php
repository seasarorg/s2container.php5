<?php
require_once(dirname(dirname(__FILE__)) . '/S2Container.php');
$time_start = microtime(true);

use \seasar\container\S2ApplicationContext as s2app;

class Service {}

class Action {
    public function setService(Service $service) {
        $this->service = $service;
    }
}


$hoge = s2app::get('Action');

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Did nothing in $time seconds\n";

$items = get_declared_interfaces();
foreach($items as $item) {
    if (preg_match('/seasar/', $item)) {
        echo "'" . $item . "'," . PHP_EOL;
    }
}

$items = get_declared_classes();
foreach($items as $item) {
    if (preg_match('/ClassLoader/', $item)) {
        continue;
    }
    if (preg_match('/Config/', $item)) {
        continue;
    }
    if (preg_match('/seasar/', $item)) {
        echo "'" . $item . "'," . PHP_EOL;
    }
}

