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
 * @package org.seasar.extension.autoregister.util
 * @author klove
 */
final class S2Container_ClassTraversal {
    public static $CLASS_SUFFIX = ".class.php";
    public static $SCANDIR_SORT = 0;
    
    /**
     * 
     */
    private function __construct()
    {
    }

    public static function forEachTime($rootDir, 
                              S2Container_ClassTraversalClassHandler $handler)
    {
        if (is_dir($rootDir)) {
            self::traverseFileSystem($rootDir,$handler);
        }
    }

    private static function traverseFileSystem($dirPath,
                           S2Container_ClassTraversalClassHandler $handler) {

        $entries = scandir($dirPath,self::$SCANDIR_SORT);
        if(!$entries){
            throw new S2Container_S2RuntimeException('ESSR0017',
                      array("invalid directory [$dirPath]"));
        }
        foreach ($entries as $entry) {
            if(preg_match("/^\./",$entry)){
                continue;
            }
            $path = $dirPath . DIRECTORY_SEPARATOR . $entry;
            if(is_dir($path)){
                self::traverseFileSystem($path,$handler);
            }else if(preg_match("/^(.+)" . self::$CLASS_SUFFIX . "$/",
                     $entry,
                     $matches)){
                $handler->processClass($path, $matches[1]);
            }
        }
    }
}
?>
