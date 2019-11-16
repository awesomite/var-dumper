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
            $this->getExceptionWith4Children()
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
    [file] =>     “(...)/tests/LightVarDumperProviders/ProviderExceptions.php:38”
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
    [file] =>    “(...)/tests/LightVarDumperProviders/ProviderExceptions.php:117”
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
    [file] =>     “(...)/tests/LightVarDumperProviders/ProviderExceptions.php:144”
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
}
