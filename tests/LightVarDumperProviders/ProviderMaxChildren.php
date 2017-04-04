<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class ProviderMaxChildren implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array();
        $result = array_merge($result, $this->getArrays());
        $result = array_merge($result, $this->getObjects());

        return new \ArrayIterator($result);
    }

    private function getArrays()
    {
        $array = array(1, 2, 3);

        $arrayDump1 = <<<'DUMP'
array(3) {
  [0] =>
  int(1)
  (...)
}

DUMP;

        $arrayDump2 = <<<'DUMP'
array(3) {
  [0] =>
  int(1)
  [1] =>
  int(2)
  [2] =>
  int(3)
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
        $object = new \stdClass();
        $object->foo = 'foo';
        $object->bar = 'bar';
        $object->foobar = 'foobar';

        $objectDump1 = <<<'DUMP'
object(stdClass) (3) {
  $foo =>
  string(3) 'foo'
  (...)
}

DUMP;

        $objectDump2 = <<<'DUMP'
object(stdClass) (3) {
  $foo =>
  string(3) 'foo'
  $bar =>
  string(3) 'bar'
  $foobar =>
  string(6) 'foobar'
}

DUMP;

        return array(
            'objectMax1' => array(1, $object, $objectDump1),
            'objectMax3' => array(3, $object, $objectDump2),
            'objectMax4' => array(4, $object, $objectDump2),
        );
    }
}
