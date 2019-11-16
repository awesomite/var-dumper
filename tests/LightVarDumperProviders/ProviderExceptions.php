<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\LightVarDumperProviders;

use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\Objects\HasherFactory;

/**
 * @internal
 */
final class ProviderExceptions implements \IteratorAggregate
{
    public function getIterator()
    {
        return new \ArrayIterator(\array_merge(
            $this->getExceptionWithHiddenStackTrace(),
            $this->getExceptionWith1Child(),
            $this->getExceptionWith2Children(),
            $this->getExceptionWith3Children(),
            $this->getExceptionWith4Children(),
            $this->getExceptionWithStackTrace(),
            $this->getExceptionWithVariadicParamsInStackTrace()
        ));
    }

    private function getExceptionWithHiddenStackTrace()
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxDepth(1);

        $exception = new \RuntimeException();

        $objectId = HasherFactory::create()->getHashId($exception);

        $expected = <<<EXPECTED
object(RuntimeException) #{$objectId} {[
    [message] =>  “”
    [code] =>     0
    [file] =>     “(...)/tests/LightVarDumperProviders/ProviderExceptions.php:40”
    [previous] => NULL
    [trace] =>    [...]
]}

EXPECTED;

        return array(
            'hidden stack trace' => array(
                $dumper, $exception, $expected,
            ),
        );
    }

    private function getExceptionWith1Child()
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxDepth(1);
        $dumper->setMaxChildren(1);

        $exception = new \LogicException('My message');

        $objectId = HasherFactory::create()->getHashId($exception);

        $expected = <<<EXPECTED
object(LogicException) #{$objectId} {[
    [message] => “My message”
    (...)
]}

EXPECTED;

        return array(
            '1 child' => array(
                $dumper, $exception, $expected,
            ),
        );
    }

    private function getExceptionWith2Children()
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxDepth(1);
        $dumper->setMaxChildren(2);

        $exception = new \UnexpectedValueException('My unexpected exception', 64);

        $objectId = HasherFactory::create()->getHashId($exception);

        $expected = <<<EXPECTED
object(UnexpectedValueException) #{$objectId} {[
    [message] => “My unexpected exception”
    [code] =>    64
    (...)
]}

EXPECTED;

        return array(
            '2 children' => array(
                $dumper, $exception, $expected,
            ),
        );
    }

    private function getExceptionWith3Children()
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxDepth(1);
        $dumper->setMaxChildren(3);

        $exception = new \LogicException('My message');

        $objectId = HasherFactory::create()->getHashId($exception);

        $expected = <<<EXPECTED
object(LogicException) #{$objectId} {[
    [message] => “My message”
    [code] =>    0
    [file] =>    “(...)/tests/LightVarDumperProviders/ProviderExceptions.php:119”
    (...)
]}

EXPECTED;

        return array(
            '3 children' => array(
                $dumper, $exception, $expected,
            ),
        );
    }

    private function getExceptionWith4Children()
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxDepth(1);
        $dumper->setMaxChildren(4);

        $exception = new \LogicException('My message');

        $objectId = HasherFactory::create()->getHashId($exception);

        $expected = <<<EXPECTED
object(LogicException) #{$objectId} {[
    [message] =>  “My message”
    [code] =>     0
    [file] =>     “(...)/tests/LightVarDumperProviders/ProviderExceptions.php:146”
    [previous] => NULL
    (...)
]}

EXPECTED;

        return array(
            '4 children' => array(
                $dumper, $exception, $expected,
            ),
        );
    }

    private function createExceptionWithStackTrace6()
    {
        return new \RangeException('My range exception');
    }

    private function createExceptionWithStackTrace5($hello, $world = 'world')
    {
        return $this->createExceptionWithStackTrace6();
    }

    private function createExceptionWithStackTrace4($a, $b, $c, $d, $e, $f)
    {
        return $this->createExceptionWithStackTrace5('hello');
    }

    private function createExceptionWithStackTrace3()
    {
        return $this->createExceptionWithStackTrace4(1, 2, 3, 4, 5, 6, 7);
    }

    public function createExceptionWithStackTrace2($mathArray)
    {
        return $this->createExceptionWithStackTrace3('first undefined parameter', 'second undefined parameter', "third\nundefined\nparameter");
    }

    private function createExceptionWithStackTrace()
    {
        $self = $this;
        $function = function () use ($self) {
            return $self->createExceptionWithStackTrace2(array(\M_PI, \M_PI_2), null);
        };

        return $function();
    }

    private function getExceptionWithStackTrace()
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxDepth(3);
        $dumper->setMaxChildren(6);
        $dumper->setMaxFileNameDepth(1);

        $exception = $this->createExceptionWithStackTrace();

        $objectId = HasherFactory::create()->getHashId($exception);

        $expected = <<<EXCPECTED
object(RangeException) #{$objectId} {[
    [message] =>  “My range exception”
    [code] =>     0
    [file] =>     “(...)/ProviderExceptions.php:170”
    [previous] => NULL
    [trace] =>
        1. (...)/ProviderExceptions.php:175 Awesomite\VarDumper\LightVarDumperProviders\ProviderExceptions->createExceptionWithStackTrace6()
        2. (...)/ProviderExceptions.php:180 Awesomite\VarDumper\LightVarDumperProviders\ProviderExceptions->createExceptionWithStackTrace5(
            hello: “hello”
        )
        3. (...)/ProviderExceptions.php:185 Awesomite\VarDumper\LightVarDumperProviders\ProviderExceptions->createExceptionWithStackTrace4(
            a: 1
            b: 2
            c: 3
            d: 4
            e: 5
            f: 6
            (...)
        )
        4. (...)/ProviderExceptions.php:190 Awesomite\VarDumper\LightVarDumperProviders\ProviderExceptions->createExceptionWithStackTrace3(
            arg1: “first undefined parameter”
            arg2: “second undefined parameter”
            arg3:
                string(25)
                    › third↵
                    › undefined↵
                    › parameter
        )
        5. (...)/ProviderExceptions.php:197 Awesomite\VarDumper\LightVarDumperProviders\ProviderExceptions->createExceptionWithStackTrace2(
            mathArray: array(2) {M_PI, M_PI_2}
            arg2:      NULL
        )
        6. (...)/ProviderExceptions.php:200 Awesomite\VarDumper\LightVarDumperProviders\ProviderExceptions->Awesomite\VarDumper\LightVarDumperProviders\{closure}()
        (...)
]}

EXCPECTED;

        return array(
            'exception with stack trace' => array(
                $dumper, $exception, $expected,
            ),
        );
    }

    private function getExceptionWithVariadicParamsInStackTrace()
    {
        if (\version_compare(\PHP_VERSION, '5.6') < 0) {
            return array();
        }

        require_once __DIR__ . \DIRECTORY_SEPARATOR . 'get_exception_php56.php';

        return array(
            'exception with variadic number of args in trace' => _test_php56_create_exception_data(),
        );
    }
}
