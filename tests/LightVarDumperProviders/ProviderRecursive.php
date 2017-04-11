<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

use Awesomite\VarDumper\Objects\Hasher;

/**
 * @internal
 */
class ProviderRecursive implements \IteratorAggregate
{
    public function getIterator()
    {
        $hasher = new Hasher();

        $recursiveObj = new \stdClass();
        $recursiveObj->self = $recursiveObj;
        $objectDump = <<<DUMP
object(stdClass) #{$hasher->getHashId($recursiveObj)} (1) {
  \$self =>
  RECURSIVE object(stdClass) #{$hasher->getHashId($recursiveObj)}
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
