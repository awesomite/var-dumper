<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class ProviderRecursive implements \IteratorAggregate
{
    public function getIterator()
    {
        $recursiveObj = new \stdClass();
        $recursiveObj->self = $recursiveObj;
        $objectDump = <<<'DUMP'
object(stdClass) (1) {
  $self =>
  RECURSIVE object(stdClass)
}

DUMP;

        $recursiveArr = array();
        $recursiveArr[] = &$recursiveArr;
        $arrayDump = <<<'DUMP'
array(1) {
  [0] =>
  RECURSIVE array(1)
}

DUMP;

        $result = array(
            array($recursiveObj, $objectDump),
            array($recursiveArr, version_compare(PHP_VERSION, '5.4.5' >= 0) ? $arrayDump : false),
        );

        return new \ArrayIterator($result);
    }
}
