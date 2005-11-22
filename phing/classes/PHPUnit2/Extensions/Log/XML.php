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

require_once 'Benchmark/Timer.php';
require_once 'PHPUnit2/Framework/AssertionFailedError.php';
require_once 'PHPUnit2/Framework/Test.php';
require_once 'PHPUnit2/Framework/TestFailure.php';
require_once 'PHPUnit2/Framework/TestListener.php';
require_once 'PHPUnit2/Framework/TestResult.php';
require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/Util/Filter.php';
require_once 'PHPUnit2/Util/Printer.php';

/**
 * A TestListener that generates an XML-based logfile
 * of the test execution.
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2002-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category    Testing
 * @package     PHPUnit2
 * @subpackage  Extensions
 * @since       2.1.0
 */
class PHPUnit2_Extensions_Log_XML extends PHPUnit2_Util_Printer implements PHPUnit2_Framework_TestListener {
    // {{{ Instance Variables

    /**
    * @var    DOMDocument
    * @access private
    */
    private $document;

    /**
    * @var    DOMElement
    * @access private
    */
    private $root;

    /**
    * @var    boolean
    * @access private
    */
    private $writeDocument = TRUE;

    /**
    * @var    DOMElement[]
    * @access private
    */
    private $testSuites = array();

    /**
    * @var    integer[]
    * @access private
    */
    private $testSuiteTests = array(0);

    /**
    * @var    integer[]
    * @access private
    */
    private $testSuiteErrors = array(0);

    /**
    * @var    integer[]
    * @access private
    */
    private $testSuiteFailures = array(0);

    /**
    * @var    integer[]
    * @access private
    */
    private $testSuiteTimes = array(0);

    /**
    * @var    integer
    * @access private
    */
    private $testSuiteLevel = 0;

    /**
    * @var    DOMElement
    * @access private
    */
    private $currentTestCase = NULL;

    /**
    * @var    Benchmark_Timer
    * @access private
    */
    private $timer;

    // }}}
    // {{{ public function __construct($out = NULL)

    /**
    * Constructor.
    *
    * @param  mixed $out
    * @access public
    */
    public function __construct($out = NULL) {
        $this->document = new DOMDocument('1.0', 'UTF-8');
        $this->document->formatOutput = TRUE;

        $this->root = $this->document->createElement('testsuites');
        $this->document->appendChild($this->root);

        $this->timer = new Benchmark_Timer;

        parent::__construct($out);
    }

    // }}}
    // {{{ public function __destruct()

    /**
    * Destructor.
    *
    * @access public
    */
    public function __destruct() {
        if ($this->writeDocument === TRUE) {
            $this->write($this->getXML());
        }

        parent::__destruct();
    }

    // }}}
    // {{{ public function addError(PHPUnit2_Framework_Test $test, Exception $e)

    /**
    * An error occurred.
    *
    * @param  PHPUnit2_Framework_Test $test
    * @param  Exception               $e
    * @access public
    */
    public function addError(PHPUnit2_Framework_Test $test, Exception $e) {
        $error = $this->document->createElement('error', PHPUnit2_Util_Filter::getFilteredStacktrace($e));
        $error->setAttribute('message', $e->getMessage());
        $error->setAttribute('type', get_class($e));

        $this->currentTestCase->appendChild($error);

        $this->testSuiteErrors[$this->testSuiteLevel]++;
    }

    // }}}
    // {{{ public function addFailure(PHPUnit2_Framework_Test $test, PHPUnit2_Framework_AssertionFailedError $e)

    /**
    * A failure occurred.
    *
    * @param  PHPUnit2_Framework_Test                 $test
    * @param  PHPUnit2_Framework_AssertionFailedError $e
    * @access public
    */
    public function addFailure(PHPUnit2_Framework_Test $test, PHPUnit2_Framework_AssertionFailedError $e) {
        $failure = $this->document->createElement('failure', PHPUnit2_Util_Filter::getFilteredStacktrace($e));
        $failure->setAttribute('message', $e->getMessage());
        $failure->setAttribute('type', get_class($e));

        $this->currentTestCase->appendChild($failure);

        $this->testSuiteFailures[$this->testSuiteLevel]++;
    }

    // }}}
    // {{{ public function addIncompleteTest(PHPUnit2_Framework_Test $test, Exception $e)

    /**
    * Incomplete test.
    *
    * @param  PHPUnit2_Framework_Test $test
    * @param  Exception               $e
    * @access public
    */
    public function addIncompleteTest(PHPUnit2_Framework_Test $test, Exception $e) {
        $error = $this->document->createElement('error', PHPUnit2_Util_Filter::getFilteredStacktrace($e));
        $error->setAttribute('message', 'Incomplete Test');
        $error->setAttribute('type', get_class($e));

        $this->currentTestCase->appendChild($error);

        $this->testSuiteErrors[$this->testSuiteLevel]++;
    }

    // }}}
    // {{{ public function startTestSuite(PHPUnit2_Framework_TestSuite $suite)

    /**
    * A testsuite started.
    *
    * @param  PHPUnit2_Framework_TestSuite $suite
    * @access public
    * @since  2.2.0
    */
    public function startTestSuite(PHPUnit2_Framework_TestSuite $suite) {
        $testSuite = $this->document->createElement('testsuite');
        $testSuite->setAttribute('name', $suite->getName());

        try {
            $class      = new ReflectionClass($suite->getName());
            $docComment = $class->getDocComment();

            if (preg_match('/@category[\s]+([\.\w]+)/', $docComment, $matches)) {
                $testSuite->setAttribute('category', $matches[1]);
            }

            if (preg_match('/@package[\s]+([\.\w]+)/', $docComment, $matches)) {
                $testSuite->setAttribute('package', $matches[1]);
            }

            if (preg_match('/@subpackage[\s]+([\.\w]+)/', $docComment, $matches)) {
                $testSuite->setAttribute('subpackage', $matches[1]);
            }
        }

        catch (ReflectionException $e) {
        }
        
        if ($this->testSuiteLevel > 0) {
            $this->testSuites[$this->testSuiteLevel]->appendChild($testSuite);
        } else {
            $this->root->appendChild($testSuite);
        }

        $this->testSuiteLevel++;
        $this->testSuites[$this->testSuiteLevel]        = $testSuite;
        $this->testSuiteTests[$this->testSuiteLevel]    = 0;
        $this->testSuiteErrors[$this->testSuiteLevel]   = 0;
        $this->testSuiteFailures[$this->testSuiteLevel] = 0;
        $this->testSuiteTimes[$this->testSuiteLevel]    = 0;
    }

    // }}}
    // {{{ public function endTestSuite(PHPUnit2_Framework_TestSuite $suite)

    /**
    * A testsuite ended.
    *
    * @param  PHPUnit2_Framework_TestSuite $suite
    * @access public
    * @since  2.2.0
    */
    public function endTestSuite(PHPUnit2_Framework_TestSuite $suite) {
        $this->testSuites[$this->testSuiteLevel]->setAttribute('tests', $this->testSuiteTests[$this->testSuiteLevel]);
        $this->testSuites[$this->testSuiteLevel]->setAttribute('failures', $this->testSuiteFailures[$this->testSuiteLevel]);
        $this->testSuites[$this->testSuiteLevel]->setAttribute('errors', $this->testSuiteErrors[$this->testSuiteLevel]);
        $this->testSuites[$this->testSuiteLevel]->setAttribute('time', $this->testSuiteTimes[$this->testSuiteLevel]);

        if ($this->testSuiteLevel > 1) {
            $this->testSuiteTests[$this->testSuiteLevel - 1]    += $this->testSuiteTests[$this->testSuiteLevel];
            $this->testSuiteErrors[$this->testSuiteLevel - 1]   += $this->testSuiteErrors[$this->testSuiteLevel];
            $this->testSuiteFailures[$this->testSuiteLevel - 1] += $this->testSuiteFailures[$this->testSuiteLevel];
            $this->testSuiteTimes[$this->testSuiteLevel - 1]    += $this->testSuiteTimes[$this->testSuiteLevel];
        }

        $this->testSuiteLevel--;
    }

    // }}}
    // {{{ public function startTest(PHPUnit2_Framework_Test $test)

    /**
    * A test started.
    *
    * @param  PHPUnit2_Framework_Test $test
    * @access public
    */
    public function startTest(PHPUnit2_Framework_Test $test) {
        $testCase = $this->document->createElement('testcase');
        $testCase->setAttribute('name', $test->getName());
        $testCase->setAttribute('class', get_class($test));

        $this->testSuites[$this->testSuiteLevel]->appendChild($testCase);
        $this->currentTestCase = $testCase;

        $this->testSuiteTests[$this->testSuiteLevel]++;

        $this->timer->start();
    }

    // }}}
    // {{{ public function endTest(PHPUnit2_Framework_Test $test)

    /**
    * A test ended.
    *
    * @param  PHPUnit2_Framework_Test $test
    * @access public
    */
    public function endTest(PHPUnit2_Framework_Test $test) {
        $this->timer->stop();
        $time = $this->timer->timeElapsed();

        $this->currentTestCase->setAttribute('time', $time);
        $this->testSuiteTimes[$this->testSuiteLevel] += $time;

        $this->currentTestCase = NULL;
    }

    // }}}
    // {{{ public function getXML()

    /**
    * Returns the XML as a string.
    *
    * @return string
    * @access public
    * @since  2.2.0
    */
    public function getXML() {
        return $this->document->saveXML();
    }

    // }}}
    // {{{ public function setWriteDocument($flag)

    /**
    * Enables or disables the writing of the document
    * in __destruct().
    *
    * This is a "hack" needed for the integration of
    * PHPUnit with Phing.
    *
    * @return string
    * @access public
    * @since  2.2.0
    */
    public function setWriteDocument($flag) {
        if (is_bool($flag)) {
            $this->writeDocument = $flag;
        }
    }

    // }}}
}

/*
 * vim600:  et sw=2 ts=2 fdm=marker
 * vim<600: et sw=2 ts=2
 */
?>
