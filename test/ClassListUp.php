<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/*
 * Copyright 2004-2005 Project Guarana Development Team
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * @package tools
 */
/**
 * @file PHPUnit2nizer.php
 * @brief package config file generator
 * @author <a href="mailto:isitoya@wakhok.ac.jp>ISITOYA Kentaro</a>
 * @version $Id$
 * 
 * Tool for generate package config file
 */

/**
 * Tool for generate package config file
 * @class PHPUnit2nizer
 */
class PHPUnit2nizer{
    /**
     * directory
     */
    private $directory = null;

    private static $phpunit2Constructor = null;
    private static $phpunit2SetUp = null;
    private static $phpunit2TearDown = null;
    private static $header = null;
    private static $classes = array(
        'S2Container_ADOdbDataSource' => '/org/seasar/extension/db/adodb/S2Container_ADOdbDataSource.class.php',
        'S2Container_ADOdbSqlHandler' => '/org/seasar/extension/db/adodb/S2Container_ADOdbSqlHandler.class.php',
        'S2Container_ADOdbTxInterceptor' => '/org/seasar/extension/db/adodb/S2Container_ADOdbTxInterceptor.class.php',
        'S2Container_AbstractBeanResultSetHandler' => '/org/seasar/extension/db/impl/S2Container_AbstractBeanResultSetHandler.class.php',
        'S2Container_AbstractDataSource' => '/org/seasar/extension/db/impl/S2Container_AbstractDataSource.class.php',
        'S2Container_ArrayResultSetHandler' => '/org/seasar/extension/db/impl/S2Container_ArrayResultSetHandler.class.php',
        'S2Container_BeanResultSetHandler' => '/org/seasar/extension/db/impl/S2Container_BeanResultSetHandler.class.php',
        'S2Container_DaoMetaDataFactoryImpl' => '/org/seasar/extension/db/impl/S2Container_DaoMetaDataFactoryImpl.class.php',
        'S2Container_DaoMetaDataImpl' => '/org/seasar/extension/db/impl/S2Container_DaoMetaDataImpl.class.php',
        'S2Container_DBSessionImpl' => '/org/seasar/extension/db/impl/S2Container_DBSessionImpl.class.php',
        'S2Container_SqlCommandImpl' => '/org/seasar/extension/db/impl/S2Container_SqlCommandImpl.class.php',
        'S2Container_AbstractTxInterceptor' => '/org/seasar/extension/db/interceptors/S2Container_AbstractTxInterceptor.class.php',
        'S2Container_SimpleDaoInterceptor' => '/org/seasar/extension/db/interceptors/S2Container_SimpleDaoInterceptor.class.php',
        'S2Container_MySQLDataSource' => '/org/seasar/extension/db/mysql/S2Container_MySQLDataSource.class.php',
        'S2Container_MySQLSqlHandler' => '/org/seasar/extension/db/mysql/S2Container_MySQLSqlHandler.class.php',
        'S2Container_MySQLTxInterceptor' => '/org/seasar/extension/db/mysql/S2Container_MySQLTxInterceptor.class.php',
        'S2Container_PearDBDataSource' => '/org/seasar/extension/db/peardb/S2Container_PearDBDataSource.class.php',
        'S2Container_PearDBSqlHandler' => '/org/seasar/extension/db/peardb/S2Container_PearDBSqlHandler.class.php',
        'S2Container_PearDBTxInterceptor' => '/org/seasar/extension/db/peardb/S2Container_PearDBTxInterceptor.class.php',
        'S2Container_PostgresDataSource' => '/org/seasar/extension/db/postgres/S2Container_PostgresDataSource.class.php',
        'S2Container_PostgresSqlHandler' => '/org/seasar/extension/db/postgres/S2Container_PostgresSqlHandler.class.php',
        'S2Container_PostgresTxInterceptor' => '/org/seasar/extension/db/postgres/S2Container_PostgresTxInterceptor.class.php',
        'S2Container_DaoMetaData' => '/org/seasar/extension/db/S2Container_DaoMetaData.class.php',
        'S2Container_DaoMetaDataFactory' => '/org/seasar/extension/db/S2Container_DaoMetaDataFactory.class.php',
        'S2Container_DaoNotFoundRuntimeException' => '/org/seasar/extension/db/S2Container_DaoNotFoundRuntimeException.class.php',
        'S2Container_DataSource' => '/org/seasar/extension/db/S2Container_DataSource.class.php',
        'S2Container_DBSession' => '/org/seasar/extension/db/S2Container_DBSession.class.php',
        'S2Container_ResultSetHandler' => '/org/seasar/extension/db/S2Container_ResultSetHandler.class.php',
        'S2Container_SqlCommand' => '/org/seasar/extension/db/S2Container_SqlCommand.class.php',
        'S2Container_SqlHandler' => '/org/seasar/extension/db/S2Container_SqlHandler.class.php',
        'S2Container_S2PHPUnitTestCase' => '/org/seasar/extension/unit/phpunit/S2Container_S2PHPUnitTestCase.class.php',
        'S2Container_S2PHPUnit2TestCase' => '/org/seasar/extension/unit/phpunit2/S2Container_S2PHPUnit2TestCase.class.php',
        'S2Container_S2SimpleInvoker' => '/org/seasar/extension/unit/simpletest/S2Container_S2SimpleInvoker.class.php',
        'S2Container_S2SimpleTestCase' => '/org/seasar/extension/unit/simpletest/S2Container_S2SimpleTestCase.class.php',
        'S2Container_AspectImpl' => '/org/seasar/framework/aop/impl/S2Container_AspectImpl.class.php',
        'S2Container_NestedMethodInvocation' => '/org/seasar/framework/aop/impl/S2Container_NestedMethodInvocation.class.php',
        'S2Container_PointcutImpl' => '/org/seasar/framework/aop/impl/S2Container_PointcutImpl.class.php',
        'S2Container_S2MethodInvocationImpl' => '/org/seasar/framework/aop/impl/S2Container_S2MethodInvocationImpl.class.php',
        'S2Container_AbstractInterceptor' => '/org/seasar/framework/aop/interceptors/S2Container_AbstractInterceptor.class.php',
        'S2Container_DelegateInterceptor' => '/org/seasar/framework/aop/interceptors/S2Container_DelegateInterceptor.class.php',
        'S2Container_InterceptorChain' => '/org/seasar/framework/aop/interceptors/S2Container_InterceptorChain.class.php',
        'S2Container_MockInterceptor' => '/org/seasar/framework/aop/interceptors/S2Container_MockInterceptor.class.php',
        'S2Container_PrototypeDelegateInterceptor' => '/org/seasar/framework/aop/interceptors/S2Container_PrototypeDelegateInterceptor.class.php',
        'S2Container_ThrowsInterceptor' => '/org/seasar/framework/aop/interceptors/S2Container_ThrowsInterceptor.class.php',
        'S2Container_TraceInterceptor' => '/org/seasar/framework/aop/interceptors/S2Container_TraceInterceptor.class.php',
        'S2Container_TraceThrowsInterceptor' => '/org/seasar/framework/aop/interceptors/S2Container_TraceThrowsInterceptor.class.php',
        'S2Container_AopProxy' => '/org/seasar/framework/aop/proxy/S2Container_AopProxy.class.php',
        'S2Container_AopProxyFactory' => '/org/seasar/framework/aop/proxy/S2Container_AopProxyFactory.class.php',
        'S2Container_AopProxyGenerator' => '/org/seasar/framework/aop/proxy/S2Container_AopProxyGenerator.class.php',
        'S2Container_DefaultAopProxy' => '/org/seasar/framework/aop/proxy/S2Container_DefaultAopProxy.class.php',
        'S2Container_UuCallAopProxy' => '/org/seasar/framework/aop/proxy/S2Container_UuCallAopProxy.class.php',
        'S2Container_UuCallAopProxyFactory' => '/org/seasar/framework/aop/proxy/S2Container_UuCallAopProxyFactory.class.php',
        'S2Container_Advice' => '/org/seasar/framework/aop/S2Container_Advice.class.php',
        'S2Container_Aspect' => '/org/seasar/framework/aop/S2Container_Aspect.class.php',
        'S2Container_Interceptor' => '/org/seasar/framework/aop/S2Container_Interceptor.class.php',
        'S2Container_Invocation' => '/org/seasar/framework/aop/S2Container_Invocation.class.php',
        'S2Container_Joinpoint' => '/org/seasar/framework/aop/S2Container_Joinpoint.class.php',
        'S2Container_MethodInterceptor' => '/org/seasar/framework/aop/S2Container_MethodInterceptor.class.php',
        'S2Container_MethodInvocation' => '/org/seasar/framework/aop/S2Container_MethodInvocation.class.php',
        'S2Container_Pointcut' => '/org/seasar/framework/aop/S2Container_Pointcut.class.php',
        'S2Container_S2MethodInvocation' => '/org/seasar/framework/aop/S2Container_S2MethodInvocation.class.php',
        'S2Container_BeanDescFactory' => '/org/seasar/framework/beans/factory/S2Container_BeanDescFactory.class.php',
        'S2Container_BeanDescImpl' => '/org/seasar/framework/beans/impl/S2Container_BeanDescImpl.class.php',
        'S2Container_PropertyDescImpl' => '/org/seasar/framework/beans/impl/S2Container_PropertyDescImpl.class.php',
        'S2Container_UuSetPropertyDescImpl' => '/org/seasar/framework/beans/impl/S2Container_UuSetPropertyDescImpl.class.php',
        'S2Container_BeanDesc' => '/org/seasar/framework/beans/S2Container_BeanDesc.class.php',
        'S2Container_ConstantNotFoundRuntimeException' => '/org/seasar/framework/beans/S2Container_ConstantNotFoundRuntimeException.class.php',
        'S2Container_FieldNotFoundRuntimeException' => '/org/seasar/framework/beans/S2Container_FieldNotFoundRuntimeException.class.php',
        'S2Container_IllegalPropertyRuntimeException' => '/org/seasar/framework/beans/S2Container_IllegalPropertyRuntimeException.class.php',
        'S2Container_MethodNotFoundRuntimeException' => '/org/seasar/framework/beans/S2Container_MethodNotFoundRuntimeException.class.php',
        'S2Container_PropertyDesc' => '/org/seasar/framework/beans/S2Container_PropertyDesc.class.php',
        'S2Container_PropertyNotFoundRuntimeException' => '/org/seasar/framework/beans/S2Container_PropertyNotFoundRuntimeException.class.php',
        'S2Container_AbstractAssembler' => '/org/seasar/framework/container/assembler/S2Container_AbstractAssembler.class.php',
        'S2Container_AbstractConstructorAssembler' => '/org/seasar/framework/container/assembler/S2Container_AbstractConstructorAssembler.class.php',
        'S2Container_AbstractMethodAssembler' => '/org/seasar/framework/container/assembler/S2Container_AbstractMethodAssembler.class.php',
        'S2Container_AbstractPropertyAssembler' => '/org/seasar/framework/container/assembler/S2Container_AbstractPropertyAssembler.class.php',
        'S2Container_AutoConstructorAssembler' => '/org/seasar/framework/container/assembler/S2Container_AutoConstructorAssembler.class.php',
        'S2Container_AutoPropertyAssembler' => '/org/seasar/framework/container/assembler/S2Container_AutoPropertyAssembler.class.php',
        'S2Container_ConstructorAssembler' => '/org/seasar/framework/container/assembler/S2Container_ConstructorAssembler.class.php',
        'S2Container_DefaultConstructorAssembler' => '/org/seasar/framework/container/assembler/S2Container_DefaultConstructorAssembler.class.php',
        'S2Container_DefaultDestroyMethodAssembler' => '/org/seasar/framework/container/assembler/S2Container_DefaultDestroyMethodAssembler.class.php',
        'S2Container_DefaultInitMethodAssembler' => '/org/seasar/framework/container/assembler/S2Container_DefaultInitMethodAssembler.class.php',
        'S2Container_DefaultPropertyAssembler' => '/org/seasar/framework/container/assembler/S2Container_DefaultPropertyAssembler.class.php',
        'S2Container_ExpressionConstructorAssembler' => '/org/seasar/framework/container/assembler/S2Container_ExpressionConstructorAssembler.class.php',
        'S2Container_ManualConstructorAssembler' => '/org/seasar/framework/container/assembler/S2Container_ManualConstructorAssembler.class.php',
        'S2Container_ManualPropertyAssembler' => '/org/seasar/framework/container/assembler/S2Container_ManualPropertyAssembler.class.php',
        'S2Container_MethodAssembler' => '/org/seasar/framework/container/assembler/S2Container_MethodAssembler.class.php',
        'S2Container_PropertyAssembler' => '/org/seasar/framework/container/assembler/S2Container_PropertyAssembler.class.php',
        'S2Container_AbstractComponentDeployer' => '/org/seasar/framework/container/deployer/S2Container_AbstractComponentDeployer.class.php',
        'S2Container_ComponentDeployer' => '/org/seasar/framework/container/deployer/S2Container_ComponentDeployer.class.php',
        'S2Container_ComponentDeployerFactory' => '/org/seasar/framework/container/deployer/S2Container_ComponentDeployerFactory.class.php',
        'S2Container_OuterComponentDeployer' => '/org/seasar/framework/container/deployer/S2Container_OuterComponentDeployer.class.php',
        'S2Container_PrototypeComponentDeployer' => '/org/seasar/framework/container/deployer/S2Container_PrototypeComponentDeployer.class.php',
        'S2Container_RequestComponentDeployer' => '/org/seasar/framework/container/deployer/S2Container_RequestComponentDeployer.class.php',
        'S2Container_SessionComponentDeployer' => '/org/seasar/framework/container/deployer/S2Container_SessionComponentDeployer.class.php',
        'S2Container_SingletonComponentDeployer' => '/org/seasar/framework/container/deployer/S2Container_SingletonComponentDeployer.class.php',
        'S2ContainerBuilder' => '/org/seasar/framework/container/factory/S2ContainerBuilder.class.php',
        'S2ContainerFactory' => '/org/seasar/framework/container/factory/S2ContainerFactory.class.php',
        'S2Container_CircularIncludeRuntimeException' => '/org/seasar/framework/container/factory/S2Container_CircularIncludeRuntimeException.class.php',
        'S2Container_IniS2ContainerBuilder' => '/org/seasar/framework/container/factory/S2Container_IniS2ContainerBuilder.class.php',
        'S2Container_SingletonS2ContainerFactory' => '/org/seasar/framework/container/factory/S2Container_SingletonS2ContainerFactory.class.php',
        'S2Container_XmlS2ContainerBuilder' => '/org/seasar/framework/container/factory/S2Container_XmlS2ContainerBuilder.class.php',
        'S2ContainerComponentDef' => '/org/seasar/framework/container/impl/S2ContainerComponentDef.class.php',
        'S2ContainerImpl' => '/org/seasar/framework/container/impl/S2ContainerImpl.class.php',
        'S2Container_ArgDefImpl' => '/org/seasar/framework/container/impl/S2Container_ArgDefImpl.class.php',
        'S2Container_AspectDefImpl' => '/org/seasar/framework/container/impl/S2Container_AspectDefImpl.class.php',
        'S2Container_ComponentDefImpl' => '/org/seasar/framework/container/impl/S2Container_ComponentDefImpl.class.php',
        'S2Container_DestroyMethodDefImpl' => '/org/seasar/framework/container/impl/S2Container_DestroyMethodDefImpl.class.php',
        'S2Container_InitMethodDefImpl' => '/org/seasar/framework/container/impl/S2Container_InitMethodDefImpl.class.php',
        'S2Container_MetaDefImpl' => '/org/seasar/framework/container/impl/S2Container_MetaDefImpl.class.php',
        'S2Container_MethodDefImpl' => '/org/seasar/framework/container/impl/S2Container_MethodDefImpl.class.php',
        'S2Container_PropertyDefImpl' => '/org/seasar/framework/container/impl/S2Container_PropertyDefImpl.class.php',
        'S2Container_SimpleComponentDef' => '/org/seasar/framework/container/impl/S2Container_SimpleComponentDef.class.php',
        'S2Container_TooManyRegistrationComponentDefImpl' => '/org/seasar/framework/container/impl/S2Container_TooManyRegistrationComponentDefImpl.class.php',
        'S2Container' => '/org/seasar/framework/container/S2Container.class.php',
        'S2Container_ArgDef' => '/org/seasar/framework/container/S2Container_ArgDef.class.php',
        'S2Container_ArgDefAware' => '/org/seasar/framework/container/S2Container_ArgDefAware.class.php',
        'S2Container_AspectDef' => '/org/seasar/framework/container/S2Container_AspectDef.class.php',
        'S2Container_AspectDefAware' => '/org/seasar/framework/container/S2Container_AspectDefAware.class.php',
        'S2Container_ClassUnmatchRuntimeException' => '/org/seasar/framework/container/S2Container_ClassUnmatchRuntimeException.class.php',
        'S2Container_ComponentDef' => '/org/seasar/framework/container/S2Container_ComponentDef.class.php',
        'S2Container_ComponentNotFoundRuntimeException' => '/org/seasar/framework/container/S2Container_ComponentNotFoundRuntimeException.class.php',
        'S2Container_ContainerConstants' => '/org/seasar/framework/container/S2Container_ContainerConstants.class.php',
        'S2Container_ContainerNotRegisteredRuntimeException' => '/org/seasar/framework/container/S2Container_ContainerNotRegisteredRuntimeException.class.php',
        'S2Container_CyclicReferenceRuntimeException' => '/org/seasar/framework/container/S2Container_CyclicReferenceRuntimeException.class.php',
        'S2Container_DestroyMethodDef' => '/org/seasar/framework/container/S2Container_DestroyMethodDef.class.php',
        'S2Container_DestroyMethodDefAware' => '/org/seasar/framework/container/S2Container_DestroyMethodDefAware.class.php',
        'S2Container_IllegalConstructorRuntimeException' => '/org/seasar/framework/container/S2Container_IllegalConstructorRuntimeException.class.php',
        'S2Container_IllegalMethodRuntimeException' => '/org/seasar/framework/container/S2Container_IllegalMethodRuntimeException.class.php',
        'S2Container_InitMethodDef' => '/org/seasar/framework/container/S2Container_InitMethodDef.class.php',
        'S2Container_InitMethodDefAware' => '/org/seasar/framework/container/S2Container_InitMethodDefAware.class.php',
        'S2Container_MetaDef' => '/org/seasar/framework/container/S2Container_MetaDef.class.php',
        'S2Container_MetaDefAware' => '/org/seasar/framework/container/S2Container_MetaDefAware.class.php',
        'S2Container_MethodDef' => '/org/seasar/framework/container/S2Container_MethodDef.class.php',
        'S2Container_PropertyDef' => '/org/seasar/framework/container/S2Container_PropertyDef.class.php',
        'S2Container_PropertyDefAware' => '/org/seasar/framework/container/S2Container_PropertyDefAware.class.php',
        'S2Container_TooManyRegistrationComponentDef' => '/org/seasar/framework/container/S2Container_TooManyRegistrationComponentDef.class.php',
        'S2Container_TooManyRegistrationRuntimeException' => '/org/seasar/framework/container/S2Container_TooManyRegistrationRuntimeException.class.php',
        'S2Container_AopProxyUtil' => '/org/seasar/framework/container/util/S2Container_AopProxyUtil.class.php',
        'S2Container_ArgDefSupport' => '/org/seasar/framework/container/util/S2Container_ArgDefSupport.class.php',
        'S2Container_AspectDefSupport' => '/org/seasar/framework/container/util/S2Container_AspectDefSupport.class.php',
        'S2Container_AutoBindingUtil' => '/org/seasar/framework/container/util/S2Container_AutoBindingUtil.class.php',
        'S2Container_DestroyMethodDefSupport' => '/org/seasar/framework/container/util/S2Container_DestroyMethodDefSupport.class.php',
        'S2Container_InitMethodDefSupport' => '/org/seasar/framework/container/util/S2Container_InitMethodDefSupport.class.php',
        'S2Container_InstanceModeUtil' => '/org/seasar/framework/container/util/S2Container_InstanceModeUtil.class.php',
        'S2Container_MetaDefSupport' => '/org/seasar/framework/container/util/S2Container_MetaDefSupport.class.php',
        'S2Container_PropertyDefSupport' => '/org/seasar/framework/container/util/S2Container_PropertyDefSupport.class.php',
        'S2Container_EmptyRuntimeException' => '/org/seasar/framework/exception/S2Container_EmptyRuntimeException.class.php',
        'S2Container_IllegalArgumentException' => '/org/seasar/framework/exception/S2Container_IllegalArgumentException.class.php',
        'S2Container_InstantiationException' => '/org/seasar/framework/exception/S2Container_InstantiationException.class.php',
        'S2Container_InvocationTargetRuntimeException' => '/org/seasar/framework/exception/S2Container_InvocationTargetRuntimeException.class.php',
        'S2Container_NoSuchMethodRuntimeException' => '/org/seasar/framework/exception/S2Container_NoSuchMethodRuntimeException.class.php',
        'S2Container_S2RuntimeException' => '/org/seasar/framework/exception/S2Container_S2RuntimeException.class.php',
        'S2Container_UnsupportedOperationException' => '/org/seasar/framework/exception/S2Container_UnsupportedOperationException.class.php',
        'S2Container_S2LogFactory' => '/org/seasar/framework/log/S2Container_S2LogFactory.class.php',
        'S2Container_S2Logger' => '/org/seasar/framework/log/S2Container_S2Logger.class.php',
        'S2Container_SimpleLogger' => '/org/seasar/framework/log/S2Container_SimpleLogger.class.php',
        'S2Container_ClassUtil' => '/org/seasar/framework/util/S2Container_ClassUtil.class.php',
        'S2Container_ConstructorUtil' => '/org/seasar/framework/util/S2Container_ConstructorUtil.class.php',
        'S2Container_EvalUtil' => '/org/seasar/framework/util/S2Container_EvalUtil.class.php',
        'S2Container_FileCacheUtil' => '/org/seasar/framework/util/S2Container_FileCacheUtil.class.php',
        'S2Container_MethodUtil' => '/org/seasar/framework/util/S2Container_MethodUtil.class.php',
        'S2Container_StringUtil' => '/org/seasar/framework/util/S2Container_StringUtil.class.php');

    /**
     * constructor
     */
    function __construct(){
         self::$header = <<<EOD
<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
/**
 * @package %s
 */
/**
 * @file %s
 * @author %s
 * @version \$Id$
 */

require_once "PHPUnit2/Framework/TestCase.php";%s

/**
 * @class %s
 */
EOD;
         self::$phpunit2Constructor = <<<EOD
    /**
     * Construct Testcase
     */
    public function __construct($name) {
         parent::__construct($name);
    }
EOD;
         self::$phpunit2SetUp = <<<EOD
    /**
     * Setup Testcase
     */
    public function setUp() {
        parent::setUp();
    }
EOD;
         self::$phpunit2TearDown = <<<EOD
    /**
     * Clean up Testcase
     */
    public function tearDown() {
         parent::tearDown();
    }
EOD;
    }

    /**
     * main entry point function
     * @param args array arguments
     */
    public static function main($args){
        $generator= new PHPUnit2nizer();
        try{
            $generator->convert($args);
            echo "\nConversion Succeeded.\n";
        }catch(Exception $e){
            echo "  " . $e->getMessage() . "\n\n";
            $generator->printUsage();
        }
    }
    
    /**
     * convert files
     * @param args array arguments
     * @return void
     */
    public function convert($args){
        $this->parseArguments($args);
        $files = $this->listFiles($this->directory);
        $classes = array();
        foreach($files as $file){
            $code = file_get_contents($file);
            
            if(preg_match("/class\s+([^ {]+)/", $code, $regs) ||
               preg_match("/interface\s+([^ {]+)/", $code, $regs)){
                $file = preg_replace('/\/{2,}/', '/', $file);
                $classes[$regs[1]] = $file;
            }
        }
        echo "    private static \$classes = array(";
        foreach($classes as $class => $path){
            echo "\"$class\" => \"$path\",\n";
        }
        echo ");";
    }

    /**
     * list Test
     * @param $directory target directory
     * @return array array of test paths
     */
    public function listFiles($directory){
        $dir = dir($directory);
        $paths = array();
        while (false !== ($entry = $dir->read())) {
            $path = "$directory/$entry";
            if(preg_match("/\.php$/",$entry) && is_readable($path)){
                $paths[] = $path; 
            }else if(is_dir($path) && $entry != "." && $entry != ".."){
                $dirs = $this->listFiles($path);
                $paths = array_merge($dirs, $paths);
            }
        }
        $dir->close();
        sort($paths);
        return $paths;
    }

    /**
     * parse argument
     * @param args array arguments
     */
    private function parseArguments($args){
        reset($args);
        while($arg = current($args)){
            switch($arg){
            case "-d" :
            case "--directory" :
                $directory = next($args);
                $this->setDirectory($directory);
                break;
                
            case "?" :
            case "-h" :
            case "-?" :
            case "--help" :
                $this->printUsage();
            }
            next($args);
        }
        if(is_null($this->directory)){
            echo "  Please specify directory with -d option \n\n";
            $this->printUsage();
        }
    }    

    /**
     * get directory
     * @return string directory
     */
    public function getDirectory(){
        return $this->directory;
    }
    
    /**
     * set directory
     * @param $directory string dirname
     */
    private function setDirectory($directory){
        if(is_dir($directory)){
            $this->directory = $directory;
        }else{
            throw new Exception("directroy not found or not readable: $directory");
        }
    }

    /**
     * print tool usage
     */
	public function printUsage(){
        echo <<<EOD
  Help of PHPUnit2nizer.
  Requires PHP version 5.0.4 or higher.

  Usage:
  	  php -f PHPUnit2nizer.php -- [Options]

	  ?|-?|-h|--help
            Show this help.
	  -d|--directory
            set directory

EOD;
        exit();
	}
}

// call main
$args = $_SERVER["argv"];
array_shift($args);
PHPUnit2nizer::main($args);
?>