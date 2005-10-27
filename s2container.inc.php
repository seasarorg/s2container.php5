<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
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
 * DICON XML format DTD Validation Switch
 */
define('S2CONTAINER_PHP5_DOM_VALIDATE',true);

/**
 * Messages Resouce File
 */
define('S2CONTAINER_PHP5_MESSAGES_INI',S2CONTAINER_PHP5 . '/SSRMessages.properties');

/**
 * S2Container.PHP5 Log Level 
 */
//define('S2CONTAINER_PHP5_LOG_LEVEL',SimpleLogger::WARN);

/**
 * SingletonS2ContainerFactory app.dicon
 */
//define('S2CONTAINER_PHP5_APP_DICON','app.dicon');

/**
 * S2Container.PHP5 Class Loader fot Autoload
 */
require_once(S2CONTAINER_PHP5 . '/S2ContainerClassLoader.class.php');

/**
 * S2Container.PHP5 Core Classes
 */
//require_once(S2CONTAINER_PHP5 . '/s2container.core.classes.php');

/**
 * Autoload Function
*/ 
function __autoload($class=null){
    if(S2ContainerClassLoader::load($class)){return;}
}

?>