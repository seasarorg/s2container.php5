<?php
interface HogeService {
    public function m1($a,$b);
    public function m2($a,$b);
    public function m3($a,$b);
}

class HogeServiceImpl implements HogeService{
    public function m1($a,$b){
        return $a + $b;
    }

    public function m2($a,$b){
        return $a * $b;
    }

    public function m3($a,$b){
        return $a - $b;
    }
}

interface HogeBean {
    public function m1($a,$b);
}

class HogeBeanImpl implements HogeBean{
    public function m1($a,$b){
        return $a + $b;
    }
}

require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

$PATH = EXAMPLE_DIR . "/autoregister/autoInterface.dicon";

$container = S2ContainerFactory::create($PATH);
$container->init();
$service = $container->getComponent('HogeService');
$bean = $container->getComponent('HogeBean');
$a = 1;
$b = 2;

//print get_class($service) . "\n";
//print get_class($bean) . "\n";
print "$a + $b = " . $service->m1($a,$b) . "\n";
print "$a * $b = " . $service->m2($a,$b) . "\n";
print "$a - $b = " . $service->m3($a,$b) . "\n";

print "$a + $b = " . $bean->m1($a,$b) . "\n";

?>
