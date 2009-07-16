<?php
system('php build.php');

define('ROOT_DIR', dirname(dirname(__FILE__)));

if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "");
}
require_once('PHPUnit/TextUI/TestRunner.php');
require_once(ROOT_DIR . '/test/test.inc.php');

$suite = new PHPUnit_Framework_TestSuite('Unit Test');
$testDir = ROOT_DIR . '/test';
isset($argv[1]) ? $targetPattern = $argv[1] : $targetPattern = '.*Test';
$testClasses = array();
find_test($testDir, $testClasses, $namespace = array());
foreach($testClasses as $testFile => $testClass) {
    if (!preg_match('/' . $targetPattern . '/', $testFile)) {
        continue;
    }
    require_once($testFile);
    $classRef = new \ReflectionClass($testClass);
    if ($classRef->isAbstract() or 
        $classRef->isInterface() or 
        !$classRef->isSubclassOf(new \ReflectionClass('PHPUnit_Framework_TestCase'))) {
        continue;
    }
    $suite->addTest(new PHPUnit_Framework_TestSuite($classRef));
}
PHPUnit_TextUI_TestRunner::run($suite);

function find_test($dirPath, &$spool, $namespace) {
    $iterator = new \DirectoryIterator($dirPath);
    while($iterator->valid()) {
        if ($iterator->isDot()) {
            $iterator->next();
            continue;
        }
        if ($iterator->isFile()) {
            $matches = array();
            if (preg_match('/^([^\.]+?Test)\..*php$/', $iterator->getFileName(), $matches)) {
                $className = implode('\\', array_merge($namespace, (array)$matches[1]));
                $spool[$iterator->getRealPath()] = $className;
            }
        } else if ($iterator->isDir()) {
            find_test($iterator->getRealPath(), $spool, array_merge($namespace, (array)$iterator->getFileName()));
        }
        $iterator->next();
    }
}
