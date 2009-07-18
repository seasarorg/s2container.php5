<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
define('S2CONTAINER_ROOT_DIR', ROOT_DIR);
require_once(ROOT_DIR . '/classes/seasar/util/ClassLoader.php');
seasar\util\ClassLoader::$CLASSES = array();
seasar\util\ClassLoader::import(ROOT_DIR . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'seasar', $namespace = array('seasar'));
$coreClasses = getCoreClasses();
genCoreFile($coreClasses);
setupClassLoader($coreClasses);


exit;

/** FUNCTIONS */
function setupClassLoader(array $coreClasses) {
    $contents = 'ClassLoader::$CLASSES = array(' . "\n";
    $classes = array();
    foreach(\seasar\util\ClassLoader::$CLASSES as $className => $filePath) {
#        if (in_array($className, $coreClasses)) {
#            continue;
#        }
        $filePath = str_replace(ROOT_DIR, '', $filePath);
        $filePath = str_replace(DIRECTORY_SEPARATOR, '/', $filePath);
        $classes[] = '    ' . "'$className' => S2CONTAINER_ROOT_DIR . '$filePath'";
    }
    $contents .= implode(',' . "\n", $classes);
    $contents .= ');' . "\n";

    $classLoaderContents = file_get_contents(ROOT_DIR . '/classes/seasar/util/ClassLoader.php');
    $classLoaderContents = preg_replace('/}[^}]+$/', '', $classLoaderContents);

    $classLoaderContents .= '}' . "\n" . "\n" . $contents;
    //print $classLoaderContents;
    file_put_contents(ROOT_DIR . '/classes/seasar/util/ClassLoader.php', $classLoaderContents);
}

function genCoreFile(array $coreClasses) {
    $coreSrc = array();
    foreach($coreClasses as &$coreClass) {
        $coreClass = trim($coreClass);
        $classFile = ROOT_DIR . '/classes/' .str_replace('\\', DIRECTORY_SEPARATOR, $coreClass) . '.php';
        $coreSrc = array_merge($coreSrc, file($classFile));
    }

    $src = '';
    foreach($coreSrc as $line) {
        if (preg_match('/^\/\//', trim($line))) {
            continue;
        }
        $line = preg_replace('/^<\?php/s', '', $line);
        $line = preg_replace('/\?>/s', '', $line);
        if (trim($line) === '') {
            continue;
        }
        $src .= $line;
    }
    $src = preg_replace('/\/\*.+?\*\//s', '', $src);
    $src = '<?php' . "\n" . $src;
    file_put_contents(ROOT_DIR . '/S2ContainerCore.php', $src);
}

function getCoreClasses() { return array(
'seasar\log\LoggerFactory',
'seasar\container\S2Container',
'seasar\container\ComponentDef',
'seasar\container\InstanceDef',
'seasar\container\AutoBindingDef',
'seasar\beans\PropertyDesc',
'seasar\exception\S2RuntimeException',
'seasar\container\S2ApplicationContext',
'seasar\log\S2Logger',
'seasar\log\impl\SimpleLogger',
'seasar\log\impl\SimpleLoggerFactory',
'seasar\container\impl\S2ContainerImpl',
'seasar\container\impl\SimpleComponentDef',
'seasar\container\factory\ComponentDefBuilder',
'seasar\util\Annotation',
'seasar\exception\AnnotationNotFoundException',
'seasar\container\impl\ComponentDefImpl',
'seasar\container\deployer\InstanceSingletonDef',
'seasar\container\deployer\InstancePrototypeDef',
'seasar\container\deployer\InstanceDefFactory',
'seasar\container\assembler\AutoBindingAutoDef',
'seasar\container\assembler\AutoBindingNoneDef',
'seasar\container\assembler\AutoBindingDefFactory',
'seasar\beans\BeanDescFactory',
'seasar\beans\BeanDesc',
'seasar\util\StringUtil',
'seasar\beans\AbstractPropertyDesc',
'seasar\beans\AccessorMethodPropertyDesc',
'seasar\util\ClassUtil',
'seasar\container\deployer\AbstractComponentDeployer',
'seasar\container\deployer\SingletonComponentDeployer',
'seasar\container\assembler\ClosureConstructorAssembler',
'seasar\container\assembler\ManualPropertyAssembler',
'seasar\container\assembler\AutoPropertyAssembler',
'seasar\container\util\ConstructorUtil'
);}

