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

use Awesomite\VarDumper\Objects\HasherFactory;

/**
 * @internal
 */
class ProviderMaxChildren implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array();
        $result = \array_merge($result, $this->getArrays());
        $result = \array_merge($result, $this->getObjects());

        return new \ArrayIterator($result);
    }

    private function getArrays()
    {
        $array = \range(1, 11);

        $arrayDump1 = <<<'DUMP'
array(11) {
    [0] => 1
    (...)
}

DUMP;

        $arrayDump2 = <<<'DUMP'
array(11) {
    [0] =>  1
    [1] =>  2
    [2] =>  3
    [3] =>  4
    [4] =>  5
    [5] =>  6
    [6] =>  7
    [7] =>  8
    [8] =>  9
    [9] =>  10
    [10] => 11
}

DUMP;

        return array(
            'arrayMax1' => array(1, $array, $arrayDump1),
            'arrayMax11' => array(11, $array, $arrayDump2),
            'arrayMax12' => array(12, $array, $arrayDump2),
        );
    }

    private function getObjects()
    {
        $hasher = HasherFactory::create();

        $object = new \stdClass();
        $object->foo = 'foo';
        $object->bar = 'bar';
        $object->foobar = 'foobar';

        $objectDump1 = <<<DUMP
object(stdClass) #{$hasher->getHashId($object)} (3) {
    \$foo => “foo”
    (...)
}

DUMP;

        $objectDump2 = <<<DUMP
object(stdClass) #{$hasher->getHashId($object)} (3) {
    \$foo =>    “foo”
    \$bar =>    “bar”
    \$foobar => “foobar”
}

DUMP;

        return array(
            'objectMax1' => array(1, $object, $objectDump1),
            'objectMax3' => array(3, $object, $objectDump2),
            'objectMax4' => array(4, $object, $objectDump2),
        );
    }
}
