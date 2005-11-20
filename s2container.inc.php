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
// $Id$
/**
 * @author klove
 */

/**
 * S2Container.PHP5 ROOT Directory
 */
define('S2CONTAINER_PHP5',dirname(__FILE__).'/src/s2container.php5');

/**
 * S2Container.PHP5 Class Loader fot Autoload
 */
require_once(S2CONTAINER_PHP5 . '/S2ContainerClassLoader.class.php');

/**
 * S2Container.PHP5 Core Classes
 */
require_once(S2CONTAINER_PHP5 . '/s2container.core.classes.php');


/**
 * Messages Resouce File
 */
require_once(S2CONTAINER_PHP5 .'/org/seasar/framework/util/S2Container_MessageUtil.class.php');
if( class_exists("S2Container_MessageUtil") ){
    S2Container_MessageUtil::addMessageResource(
                       S2CONTAINER_PHP5 . '/SSRMessages.properties');
}

/**
 * DICON XML format DTD Validation Switch
 */
define('S2CONTAINER_PHP5_DOM_VALIDATE',false);

/**
 * S2Container_SingletonS2ContainerFactory app.dicon
 */
//define('S2CONTAINER_PHP5_APP_DICON','app.dicon');

/**
 * S2Container.PHP5 Log Level 
 */
//define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::WARN);


/**
 * S2AOP enhanced proxy class file cache.
 */
//define('S2AOP_PHP5_FILE_CACHE',false);
//define('S2AOP_PHP5_FILE_CACHE_DIR','/path/to/cache/dir');

/**
 * Autoload Function
 */
//function __autoload($class=null){
//    if(S2ContainerClassLoader::load($class)){return;}
//}
?>