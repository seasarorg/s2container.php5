<?php
//
// +------------------------------------------------------------------------+
// | PEAR :: PHPUnit2                                                       |
// +------------------------------------------------------------------------+
// | Copyright (c) 2002-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>. |
// +------------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,        |
// | that is available at http://www.php.net/license/3_0.txt.               |
// | If you did not receive a copy of the PHP license and are unable to     |
// | obtain it through the world-wide-web, please send a note to            |
// | license@php.net so we can mail you a copy immediately.                 |
// +------------------------------------------------------------------------+
//
// $Id$
//

require_once 'PHPUnit2/Runner/TestSuiteLoader.php';

/**
 * The standard test suite loader.
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2002-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category    Testing
 * @package     PHPUnit2
 * @subpackage  Runner
 */
class PHPUnit2_Runner_StandardTestSuiteLoader implements PHPUnit2_Runner_TestSuiteLoader {
    // {{{ public function load($suiteClassName, $suiteClassFile = '')

    /**
    * @param  string  $suiteClassName
    * @param  string  $suiteClassFile
    * @return ReflectionClass
    * @access public
    */
    public function load($suiteClassName, $suiteClassFile = '') {
        $suiteClassName = str_replace('.php', '', $suiteClassName);
        $suiteClassFile = !empty($suiteClassFile) ? $suiteClassFile : str_replace('_', '/', $suiteClassName) . '.php';

        if (!class_exists($suiteClassName)) {
            if(!file_exists($suiteClassFile)) {
                $includePaths = explode(PATH_SEPARATOR, get_include_path());

                foreach ($includePaths as $includePath) {
                    $file = $includePath . DIRECTORY_SEPARATOR . $suiteClassFile;

                    if (file_exists($file)) {
                        $suiteClassFile = $file;
                        break;
                    }
                }
            }

            @include_once $suiteClassFile;

            if (!class_exists($suiteClassName)) {
                @include_once './' . $suiteClassFile;
            }
        }

        if (class_exists($suiteClassName)) {
            return new ReflectionClass($suiteClassName);
        } else {
            throw new Exception(
              sprintf(
                'Class %s could not be found in %s.',

                $suiteClassName,
                $suiteClassFile
              )
            );
        }
    }

    // }}}
    // {{{ public function reload(ReflectionClass $aClass)

    /**
    * @param  ReflectionClass  $aClass
    * @return ReflectionClass
    * @access public
    */
    public function reload(ReflectionClass $aClass) {
        return $aClass;
    }

    // }}}
}

/*
 * vim600:  et sw=2 ts=2 fdm=marker
 * vim<600: et sw=2 ts=2
 */
?>
