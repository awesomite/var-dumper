<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class ProviderMaxDepth implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array();
        $result = array_merge($result, $this->getObjects());
        $result['array'] = $this->getArray();

        return new \ArrayIterator($result);
    }

    private function getObjects()
    {
        $obj = new \stdClass();
        $obj->testInt = 5;
        $obj->foo = new \stdClass();
        $obj->foo->bar = new \stdClass();

        $dump1 = <<<'DUMP'
object(stdClass) (2) {
  $testInt =>
  int(5)
  $foo =>
  object(stdClass) (1) {
    ...
  }
}

DUMP;

        $dump2 = <<<'DUMP'
object(stdClass) (2) {
  $testInt =>
  int(5)
  $foo =>
  object(stdClass) (1) {
    $bar =>
    object(stdClass) (0) {
    }
  }
}

DUMP;

        return array(
            'objectDepth1' => array(1, $obj, $dump1),
            'objectDepth2' => array(2, $obj, $dump2),
            'objectDepth3' => array(3, $obj, $dump2),
        );
    }

    private function getArray()
    {
        $array = array(
            'foo' => array(
                'bar',
            ),
        );

        $dumpArray = <<<'DUMP'
array(1) {
  [foo] =>
  array(1) {
    ...
  }
}

DUMP;

        return array(1, $array, $dumpArray);
    }
}
