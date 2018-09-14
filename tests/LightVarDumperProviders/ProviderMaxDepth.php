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
final class ProviderMaxDepth implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array();
        $result = \array_merge($result, $this->getObjects());
        $result['array'] = $this->getArray();

        return new \ArrayIterator($result);
    }

    private function getObjects()
    {
        $hasher = HasherFactory::create();

        $obj = new \stdClass();
        $obj->testInt = 5;
        $obj->foo = new \stdClass();
        $obj->foo->bar = new \stdClass();

        $dump1
            = <<<DUMP
object(stdClass) #{$hasher->getHashId($obj)} (2) {
    \$testInt => 5
    \$foo =>     object(stdClass) #{$hasher->getHashId($obj->foo)} (1) {...}
}

DUMP;

        $dump2
            = <<<DUMP
object(stdClass) #{$hasher->getHashId($obj)} (2) {
    \$testInt => 5
    \$foo =>
        object(stdClass) #{$hasher->getHashId($obj->foo)} (1) {
            \$bar => object(stdClass) #{$hasher->getHashId($obj->foo->bar)} (0) {}
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

        $dumpArray
            = <<<'DUMP'
array(1) {
    [foo] => array(1) {...}
}

DUMP;

        return array(1, $array, $dumpArray);
    }
}
