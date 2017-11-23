<?php

namespace Awesomite\VarDumper\Listeners;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @internal
 */
class TestListener implements \PHPUnit_Framework_TestListener
{
    private $offset = .1;

    private $messages = array();

    public function __construct()
    {
        $this->getConsoleOutput()->writeln(\sprintf('PHP %s', \phpversion()));
    }

    public function __destruct()
    {
        $output = $this->getConsoleOutput();
        foreach ($this->messages as $message) {
            $output->writeln($message);
        }
    }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function addRiskyTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }

    public function startTest(\PHPUnit_Framework_Test $test)
    {
    }

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        if ($time < $this->offset) {
            return;
        }

        $name = ($test instanceof \PHPUnit_Framework_TestCase) || ($test instanceof TestCase)
            ? \get_class($test) . '::' . $test->getName()
            : \get_class($test);

        $this->messages[] = \sprintf(
            "<warning>Test '%s' took %0.2f seconds.</warning>",
            $name,
            $time
        );
    }

    private function getConsoleOutput()
    {
        $style = new OutputFormatterStyle();
        $style->setBackground('yellow');
        $style->setForeground('black');

        $output = new ConsoleOutput();
        $output->getFormatter()->setStyle('warning', $style);

        return $output;
    }
}
