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
class ProviderRecursive implements \IteratorAggregate
{
    public function getIterator()
    {
        $hasher = HasherFactory::create();

        $recursiveObj = new \stdClass();
        $recursiveObj->self = $recursiveObj;
        $objectDump
            = <<<DUMP
object(stdClass) #{$hasher->getHashId($recursiveObj)} (1) {
    \$self => RECURSIVE object(stdClass) #{$hasher->getHashId($recursiveObj)}
}

DUMP;

        $recursiveArr = [];
        $recursiveArr[] = &$recursiveArr;
        $arrayDump
            = <<<'DUMP'
array(1) {
    [0] => RECURSIVE array(1)
}

DUMP;

        $result = [
            [$recursiveObj, $objectDump],
            [$recursiveArr, $arrayDump],
        ];

        return new \ArrayIterator($result);
    }
}
