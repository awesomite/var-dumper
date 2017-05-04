<?php

namespace Awesomite\VarDumper\Listeners;

/**
 * @internal
 */
class BridgeTestListener4x extends BridgeTestListenerBody implements \PHPUnit_Framework_TestListener
{
    final public function startTest(\PHPUnit_Framework_Test $test)
    {
        $this->_startTest($test);
    }

    final public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        $this->_endTest($test, $time);
    }

    final public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->_addError($test,  $e, $time);
    }

    final public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->_addFailure($test, $e, $time);
    }

    final public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->_addIncompleteTest($test, $e, $time);
    }

    final public function addRiskyTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->_addRiskyTest($test, $e, $time);
    }

    final public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->_addRiskyTest($test, $e, $time);
    }

    final public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $this->_endTestSuite($suite);
    }

    final public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $this->_startTestSuite($suite);
    }
}
