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

require_once 'PHPUnit2/Framework/AssertionFailedError.php';
require_once 'PHPUnit2/Framework/Test.php';
require_once 'PHPUnit2/Framework/TestSuite.php';

/**
 * A Listener for test progress.
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2002-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category    Testing
 * @package     PHPUnit2
 * @subpackage  Framework
 */
interface PHPUnit2_Framework_TestListener {
    // {{{ public function addError(PHPUnit2_Framework_Test $test, Exception $e)

    /**
    * An error occurred.
    *
    * @param  PHPUnit2_Framework_Test $test
    * @param  Exception               $e
    * @access public
    */
    public function addError(PHPUnit2_Framework_Test $test, Exception $e);

    // }}}
    // {{{ public function addFailure(PHPUnit2_Framework_Test $test, PHPUnit2_Framework_AssertionFailedError $e)

    /**
    * A failure occurred.
    *
    * @param  PHPUnit2_Framework_Test                 $test
    * @param  PHPUnit2_Framework_AssertionFailedError $e
    * @access public
    */
    public function addFailure(PHPUnit2_Framework_Test $test, PHPUnit2_Framework_AssertionFailedError $e);

    // }}}
    // {{{ public function addIncompleteTest(PHPUnit2_Framework_Test $test, Exception $e)

    /**
    * Incomplete test.
    *
    * @param  PHPUnit2_Framework_Test $test
    * @param  Exception               $e
    * @access public
    */
    public function addIncompleteTest(PHPUnit2_Framework_Test $test, Exception $e);

    // }}}
    // {{{ public function startTestSuite(PHPUnit2_Framework_TestSuite $suite)

    /**
    * A testsuite started.
    *
    * @param  PHPUnit2_Framework_TestSuite $suite
    * @access public
    * @since  2.2.0
    */
    public function startTestSuite(PHPUnit2_Framework_TestSuite $suite);

    // }}}
    // {{{ public function endTestSuite(PHPUnit2_Framework_TestSuite $suite)

    /**
    * A testsuite ended.
    *
    * @param  PHPUnit2_Framework_TestSuite $suite
    * @access public
    * @since  2.2.0
    */
    public function endTestSuite(PHPUnit2_Framework_TestSuite $suite);

    // }}}
    // {{{ public function startTest(PHPUnit2_Framework_Test $test)

    /**
    * A test started.
    *
    * @param  PHPUnit2_Framework_Test $test
    * @access public
    */
    public function startTest(PHPUnit2_Framework_Test $test);

    // }}}
    // {{{ public function endTest(PHPUnit2_Framework_Test $test)

    /**
    * A test ended.
    *
    * @param  PHPUnit2_Framework_Test $test
    * @access public
    */
    public function endTest(PHPUnit2_Framework_Test $test);

    // }}}
}

/*
 * vim600:  et sw=2 ts=2 fdm=marker
 * vim<600: et sw=2 ts=2
 */
?>
