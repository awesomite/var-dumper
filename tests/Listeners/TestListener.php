<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Listeners;

use Awesomite\VarDumper\SyntaxTest;
use Awesomite\VarDumper\TestEnv;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use PHPUnit\Framework\TestListener as PHPUnitTestListener;

/**
 * @internal
 */
class TestListener implements PHPUnitTestListener
{
    private static $times = [];

    public static function flush()
    {
        $times = self::$times;
        self::$times = [];

        if (empty($times)) {
            return;
        }

        \usort($times, function ($left, $right) {
            return $left[0] == $right[0]
                ? 0
                : ($left[0] < $right[0] ? 1 : -1);
        });


        $wholeTime = 0;
        \array_walk($times, function ($element) use (&$wholeTime) {
            $wholeTime += $element[0];
        });

        $cpTimes = \array_slice($times, 0, 10);

        $maxLength = 0;
        \array_walk($cpTimes, function ($element) use (&$maxLength) {
            $len = \mb_strlen($element[1]);
            if ($len > $maxLength) {
                $maxLength = $len;
            }
        });

        $output = new ConsoleOutput();
        $table = new Table($output);
        $table->setHeaders(['Time [ms]', '%', 'Name']);
        foreach ($cpTimes as $timeData) {
            list($time, $name) = $timeData;
            $table->addRow([
                sprintf('% 7.2f', $time * 1000),
                sprintf('% 5.2f', $time / $wholeTime * 100),
                $name
            ]);
        }
        $table->render();
    }

    public function __construct()
    {
        $output = new ConsoleOutput();
        $output->writeln(\sprintf('PHP %s', \phpversion()));
        if (TestEnv::isSpeedTest()) {
            \register_shutdown_function(function () {
                TestListener::flush();
            });
            SyntaxTest::requireWholeSrc();
        }
    }

    public function addWarning(Test $test, Warning $e, $time)
    {
    }

    public function startTest(Test $test)
    {
    }

    public function endTest(Test $test, $time)
    {
        $name = $test instanceof TestCase
            ? \get_class($test) . '::' . $test->getName()
            : \get_class($test);

        self::$times[] = [$time, $name];
    }

    public function addError(Test $test, \Exception $e, $time)
    {
    }

    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
    }

    public function addIncompleteTest(Test $test, \Exception $e, $time)
    {
    }

    public function addRiskyTest(Test $test, \Exception $e, $time)
    {
    }

    public function addSkippedTest(Test $test, \Exception $e, $time)
    {
    }

    public function startTestSuite(TestSuite $suite)
    {
    }

    public function endTestSuite(TestSuite $suite)
    {
    }
}
