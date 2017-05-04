<?php

namespace Awesomite\VarDumper\Listeners;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @internal
 */
class TestListener extends BridgeTestListener
{
    private $offset = .1;

    private $messages = array();

    public function __destruct()
    {
        $output = $this->getConsoleOutput();
        foreach ($this->messages as $message) {
            $output->writeln($message);
        }
    }

    protected function _endTest($test, $time)
    {
        if ($time < $this->offset) {
            return;
        }

        $name = ($test instanceof \PHPUnit_Framework_TestCase) || ($test instanceof TestCase)
            ? get_class($test) . '::' . $test->getName()
            : get_class($test);

        $this->messages[] = sprintf("<warning>Test '%s' took %0.2f seconds.</warning>",
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
