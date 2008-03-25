<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
define('SEASAR_ROOT_DIR', ROOT_DIR);
require_once(ROOT_DIR . '/classes/seasar/util/ClassLoader.php');
seasar::util::ClassLoader::$CLASSES = array();
seasar::util::ClassLoader::import(ROOT_DIR . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'seasar', $namespace = array('seasar'));
seasar::util::ClassLoader::import(ROOT_DIR . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'legacy', $namespace = array(), true);
$coreClasses = getCoreClasses();
genCoreFile($coreClasses);
setupClassLoader($coreClasses);

exit;

/** FUNCTIONS */
function setupClassLoader(array $coreClasses) {
    $contents = 'seasar::util::ClassLoader::$CLASSES = array(' . PHP_EOL;
    $classes = array();
    foreach(seasar::util::ClassLoader::$CLASSES as $className => $filePath) {
        if (in_array($className, $coreClasses)) {
            continue;
        }
        $filePath = str_replace(ROOT_DIR, '', $filePath);
        $filePath = str_replace(DIRECTORY_SEPARATOR, '/', $filePath);
        $classes[] = '    ' . "'$className' => SEASAR_ROOT_DIR . '$filePath'";
    }
    $contents .= implode(',' . PHP_EOL, $classes);
    $contents .= ');' . PHP_EOL;

    $classLoaderContents = file_get_contents(ROOT_DIR . '/classes/seasar/util/ClassLoader.php');
    $classLoaderContents = preg_replace('/}[^}]+$/', '', $classLoaderContents);

    $classLoaderContents .= '}' . PHP_EOL . PHP_EOL . $contents;
    //print $classLoaderContents;
    file_put_contents(ROOT_DIR . '/classes/seasar/util/ClassLoader.php', $classLoaderContents);
}

function genCoreFile(array $coreClasses) {
    $coreSrc = array();
    foreach($coreClasses as &$coreClass) {
        $coreClass = trim($coreClass);
        $classFile = ROOT_DIR . '/classes/' . preg_replace('/::/', DIRECTORY_SEPARATOR, $coreClass) . '.php';
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
    $src = '<?php' . PHP_EOL . $src;
    file_put_contents(ROOT_DIR . '/S2ContainerCore.php', $src);
}

function getCoreClasses() { return array(
'seasar::exception::S2RuntimeException',
'seasar::log::S2Logger',
'seasar::log::LoggerFactory',
'seasar::log::impl::SimpleLoggerFactory',
'seasar::log::impl::SimpleLogger',
'seasar::container::S2ApplicationContext',
'seasar::util::Annotation',
'seasar::container::S2Container',
'seasar::container::impl::S2ContainerImpl',
'seasar::container::util::MetaDefSupport',
'seasar::container::ComponentDef',
'seasar::container::impl::SimpleComponentDef',
'seasar::beans::PropertyDesc',
'seasar::beans::AbstractPropertyDesc',
'seasar::beans::AccessorMethodPropertyDesc',
'seasar::beans::PublicPropertyDesc',
'seasar::beans::BeanDesc',
'seasar::beans::BeanDescFactory',
'seasar::util::StringUtil',
'seasar::container::impl::ComponentDefImpl',
'seasar::container::util::ArgDefSupport',
'seasar::container::util::PropertyDefSupport',
'seasar::container::util::InitMethodDefSupport',
'seasar::container::util::AspectDefSupport',
'seasar::container::InstanceDef',
'seasar::container::deployer::InstanceSingletonDef',
'seasar::container::deployer::InstancePrototypeDef',
'seasar::container::deployer::InstanceDefFactory',
'seasar::container::AutoBindingDef',
'seasar::container::assembler::AutoBindingAutoDef',
'seasar::container::assembler::AutoBindingNoneDef',
'seasar::container::assembler::AutoBindingDefFactory',
'seasar::util::ClassUtil',
'seasar::container::deployer::AbstractComponentDeployer',
'seasar::container::deployer::SingletonComponentDeployer',
'seasar::container::assembler::AbstractAssembler',
'seasar::container::assembler::ManualConstructorAssembler',
'seasar::container::assembler::AutoConstructorAssembler',
'seasar::container::assembler::ManualPropertyAssembler',
'seasar::container::assembler::AutoPropertyAssembler',
'seasar::container::assembler::AbstractMethodAssembler',
'seasar::container::assembler::InitMethodAssembler',
'seasar::container::util::ConstructorUtil',
'seasar::container::factory::S2ContainerFactory',
'seasar::container::factory::S2ContainerBuilder',
'seasar::container::factory::XmlS2ContainerBuilder',
'seasar::container::impl::TooManyRegistrationComponentDef',
'seasar::aop::Pointcut',
'seasar::container::impl::ArgDef',
'seasar::container::impl::AspectDef',
'seasar::container::util::AopUtil',
'seasar::aop::S2AopFactory',
'seasar::aop::Aspect',
'seasar::util::EvalUtil',
'seasar::aop::MethodInterceptor',
'seasar::aop::EnhancedClassGenerator'
);}

