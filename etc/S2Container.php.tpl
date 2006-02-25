<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
// $Id$
/**
 * @author klove
 *
 * S2Container System Definition
 *   S2Container define these definitions.
 *   - S2CONTAINER_PHP5 : S2Container.PHP5 ROOT Directory
 *                        [ string default s2container.php5/src ]
 * 
 * User Definition
 *   User could define these definitions.
 *   - S2CONTAINER_PHP5_DOM_VALIDATE : DICON XML format DTD validation
 *                                     [ boolean default true ]
 *   - S2CONTAINER_PHP5_APP_DICON : S2Container_SingletonS2ContainerFactory uses if defined.
 *                                  [ string default not defined ] 
 *   - S2CONTAINER_PHP5_LOG_LEVEL : S2Container.PHP5 Log Level
 *                                  [ integer default S2Container_SimpleLogger::WARN ]
 *   - S2AOP_PHP5_FILE_CACHE : S2AOP proxy class file cache
 *                             [ boolean default false ]
 *   - S2AOP_PHP5_FILE_CACHE_DIR : S2AOP proxy class file cache directory
 *                             [ string default not defined ]
 *
 * Autoload function must be defined
 *   sample : use S2ContainerClassLoader
 *     function __autoload($class=null){
 *         S2ContainerClassLoader::load($class);
 *     }
 * 
 *   sample : use require_once directly
 *     function __autoload($class=null){
 *         if($class != null){
 *             require_once("$class.class.php");
 *         }
 *     }
 * 
 */

/**
 * PHP version check
 */
if(!version_compare(phpversion(), "5.1.0", ">=")){
    print "[ERROR] requirement : PHP-5.1 or later. exit.\n";
    exit;
}

/**
 * S2Container.PHP5 ROOT Directory
 */
define('S2CONTAINER_PHP5',dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src');
ini_set('include_path', 
        S2CONTAINER_PHP5 . PATH_SEPARATOR . ini_get('include_path'));

/**
 * S2Container.PHP5 Class Loader for Autoload
require_once(S2CONTAINER_PHP5 . '/S2ContainerClassLoader.class.php');
S2ContainerClassLoader::import(S2CONTAINER_PHP5);
 */

/**
 * S2Container.PHP5 Core Classes
 */
require_once('s2container.core.classes.php');

/**
 * Messages Resouce File
 */
require_once(S2CONTAINER_PHP5 .'/S2ContainerMessageUtil.class.php');
if( class_exists("S2ContainerMessageUtil") ){
    S2ContainerMessageUtil::addMessageResource(
                       S2CONTAINER_PHP5 . '/SSRMessages.properties');
}
?>
