<?php

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
        $array = array(1, 2, 3);

        $arrayDump1 = <<<'DUMP'
array(3) {
    [0] => 1
    (...)
}

DUMP;

        $arrayDump2 = <<<'DUMP'
array(3) {
    [0] => 1
    [1] => 2
    [2] => 3
}

DUMP;

        return array(
            'arrayMax1' => array(1, $array, $arrayDump1),
            'arrayMax3' => array(3, $array, $arrayDump2),
            'arrayMax4' => array(4, $array, $arrayDump2),
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
