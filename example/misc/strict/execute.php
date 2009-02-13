<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
use \seasar\container\S2ApplicationContext as s2app;

s2app::import(dirname(__FILE__) . '/classes');
s2app::registerAspect('/Service/', 'StrictInterceptor');
$service = s2app::get('Service');

try {
    print $service->a(1, 2) . PHP_EOL;
} catch(StrictException $e) {
    print $e->getMessage() . PHP_EOL;
}

try {
    $service->b(1, 2);
} catch(StrictException $e) {
    print $e->getMessage() . PHP_EOL;
}

try {
    $service->c(1, 2);
} catch(StrictException $e) {
    print $e->getMessage() . PHP_EOL;
}

try {
    $service->d(1, 2);
} catch(StrictException $e) {
    print $e->getMessage() . PHP_EOL;
}

try {
    $service->e('1', 2);
} catch(StrictException $e) {
    print $e->getMessage() . PHP_EOL;
}

class Hoge{}
try {
    $service->f(new Hoge);
} catch(StrictException $e) {
    print $e->getMessage() . PHP_EOL;
}

try {
    $service->g(array());
} catch(StrictException $e) {
    print $e->getMessage() . PHP_EOL;
}
