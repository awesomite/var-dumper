<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Awesomite\VarDumper\LightVarDumper;

require __DIR__ . \DIRECTORY_SEPARATOR . 'init.php';

/**
 * @internal
 */
class DivideByZeroException extends \Exception
{
}

/**
 * @internal
 */
class Divider
{
    public function divide($a, $b)
    {
        if (0 === $b) {
            throw new DivideByZeroException('Cannot divide by zero');
        }

        return $a / $b;
    }
}

/**
 * @internal
 */
class Calculator
{
    public static function execute($action, $numberA, $numberB)
    {
        if ('divide' == $action) {
            $divider = new Divider();

            return $divider->divide($numberA, $numberB);
        }
    }
}

try {
    $calculator = new Calculator();
    $calculator->execute('divide', 5, 0);
} catch (\Exception $exception) {
    $dumper = new LightVarDumper();
    $dumper->dump($exception);
}

/*

Output:

object(DivideByZeroException) #4 {[
    [message] =>  “Cannot divide by zero”
    [code] =>     0
    [file] =>     “(...)/examples/exception.php:31”
    [previous] => NULL
    [trace] =>
        1. (...)/examples/exception.php:48 Divider->divide(
            a: 5
            b: 0
        )
        2. (...)/examples/exception.php:55 Calculator::execute(
            action:  “divide”
            numberA: 5
            numberB: 0
        )
]}

*/
