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
 * @package org.seasar.framework.util
 * @author klove
 */
final class S2Container_FileCacheUtil 
{
    /**
     * 
     */
    private function __construct()
    {
    }
    
    /**
     * @param string file path
     * @param string file path
     * @return boolean
     */
    public static function isUpdated($file,$targetFile)
    {
        if (filemtime($targetFile) > filemtime($file)) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public static function isAopCache()
    {
        if (defined('S2AOP_PHP5_FILE_CACHE') and
           S2AOP_PHP5_FILE_CACHE and
           defined('S2AOP_PHP5_FILE_CACHE_DIR') and
           is_dir(S2AOP_PHP5_FILE_CACHE_DIR) and
           is_writable(S2AOP_PHP5_FILE_CACHE_DIR)) {
            return true;
        }
        return false;
    }

    /**
     * @param string class name
     * @param string file path
     * @return boolean
     */
    public static function loadAopCache($className,$targetClassFile)
    {
        $path = S2AOP_PHP5_FILE_CACHE_DIR . DIRECTORY_SEPARATOR . 
                $className . '.class.php';
        if (is_readable($path) and
           !S2Container_FileCacheUtil::isUpdated($path,$targetClassFile)) {
            require_once($path);
            return true;
        }
        return false;
    }

    /**
     * @param string class name
     * @param string src 
     */
    public static function saveAopCache($className,$src)
    {
        $path = S2AOP_PHP5_FILE_CACHE_DIR . DIRECTORY_SEPARATOR .
                $className . '.class.php';
        $src = '<?php ' . $src . ' ?>';
        file_put_contents($path,$src,LOCK_EX);
    }
}
?>
