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
use Awesomite\VarDumper\Objects\HasherFactory;

if (!\function_exists('_test_php56_create_exception')) {
    class _MyExceptionFactory
    {
        public static function factory()
        {
            $function = function (...$variadic) {
                return _test_php56_create_exception('first', 'second', ...$variadic);
            };

            return $function(3, 4, 5);
        }
    }

    function _test_php56_create_exception($first, $second, ...$variadic)
    {
        return new \InvalidArgumentException('Invalid argument', 100);
    }

    function _test_php56_create_exception_data()
    {
        $createException = function () {
            return _MyExceptionFactory::factory();
        };

        $dumper = new LightVarDumper();
        $dumper->setMaxChildren(5);

        $exception = $createException('Redundant parameter');

        $objectId = HasherFactory::create()->getHashId($exception);

        $expected = <<<EXPECTED
object(InvalidArgumentException) #{$objectId} {[
    [message] =>  “Invalid argument”
    [code] =>     100
    [file] =>     “(...)/tests/LightVarDumperProviders/get_exception_php56.php:30”
    [previous] => NULL
    [trace] =>
        1. (...)/tests/LightVarDumperProviders/get_exception_php56.php:21 _test_php56_create_exception(
            first:    “first”
            second:   “second”
            variadic: array(3) {3, 4, 5}
        )
        2. (...)/tests/LightVarDumperProviders/get_exception_php56.php:24 _MyExceptionFactory::{closure}(
            arg1: 3
            arg2: 4
            arg3: 5
        )
        3. (...)/tests/LightVarDumperProviders/get_exception_php56.php:36 _MyExceptionFactory::factory()
        4. (...)/tests/LightVarDumperProviders/get_exception_php56.php:42 {closure}(
            arg1: “Redundant parameter”
        )
        5. (...)/tests/LightVarDumperProviders/ProviderExceptions.php:269 _test_php56_create_exception_data()
        (...)
]}

EXPECTED;

        return array(
            $dumper,
            $exception,
            $expected,
        );
    }
}
