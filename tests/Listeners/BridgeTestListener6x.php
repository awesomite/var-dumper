<?php

namespace Awesomite\VarDumper\Listeners;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener as PHPUnitTestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;

/**
 * @internal
 */
class BridgeTestListener6x extends BridgeTestListenerBody implements PHPUnitTestListener
{
    final public function addError(Test $test, \Exception $e, $time)
    {
        $this->_addError($test, $e, $time);
    }

    final public function addWarning(Test $test, Warning $e, $time)
    {
        $this->_addWarning($test, $e, $time);
    }

    final public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
        $this->_addFailure($test, $e, $time);
    }

    final public function addIncompleteTest(Test $test, \Exception $e, $time)
    {
        $this->_addIncompleteTest($test, $e, $time);
    }

    final public function addRiskyTest(Test $test, \Exception $e, $time)
    {
        $this->_addRiskyTest($test, $e, $time);
    }

    final public function addSkippedTest(Test $test, \Exception $e, $time)
    {
        $this->_addSkippedTest($test, $e, $time);
    }

    final public function startTestSuite(TestSuite $suite)
    {
        $this->_startTestSuite($suite);
    }

    final public function endTestSuite(TestSuite $suite)
    {
        $this->_endTestSuite($suite);
    }

    final public function startTest(Test $test)
    {
        $this->_startTest($test);
    }

    final public function endTest(Test $test, $time)
    {
        $this->_endTest($test, $time);
    }
}
