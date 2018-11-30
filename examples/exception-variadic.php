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

require __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

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

        return $a/$b;
    }
}

/**
 * @internal
 */
class Controller
{
    public static function execute($class, $method, ...$params)
    {
        (new $class())->$method(...$params);
    }
}

try {
    Controller::execute(Divider::class, 'divide', 10, 0);
} catch (\Exception $exception) {
    $dumper = new LightVarDumper();
    $dumper->dump($exception);
}

/*

Output:

object(DivideByZeroException) #2 {[
    [message] =>  “Cannot divide by zero”
    [code] =>     0
    [file] =>     “(...)/examples/exception-variadic.php:31”
    [previous] => NULL
    [trace] =>
        1. (...)/examples/exception-variadic.php:45 Divider->divide(
            a: 10
            b: 0
        )
        2. (...)/examples/exception-variadic.php:50 Controller::execute(
            class:  “Divider”
            method: “divide”
            params: array(2) {10, 0}
        )
]}

*/
