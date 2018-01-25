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
class ProviderPlaceInCode implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = [
            'simple_array'           => $this->getSimpleArray(),
            'nested_array'           => $this->getNestedArray(),
            'array_with_object'      => $this->getArrayWithObject(),
            'array_with_single_line' => $this->getSingleLineString(),
        ];

        return new \ArrayIterator($result);
    }

    private function getSimpleArray()
    {
        $dumper = new LightVarDumper(true);
        $var = \range(1, 5);
        list($file, $line) = $this->getTestFileLine();
        $dump = "{$file}:{$line}:\narray(5) {1, 2, 3, 4, 5}\n";

        return [
            $dumper,
            $var,
            $dump,
        ];
    }

    private function getNestedArray()
    {
        $dumper = new LightVarDumper(true);
        $var = [
            1,
            null,
            [
                1,
                2,
                3,
                4,
                5,
                6,
            ],
        ];
        list($file, $line) = $this->getTestFileLine();
        $dump
            = <<<DUMP
{$file}:{$line}:
array(3) {
    [0] => 1
    [1] => NULL
    [2] =>
        array(6) {
            [0] => 1
            [1] => 2
            [2] => 3
            [3] => 4
            [4] => 5
            [5] => 6
        }
}

DUMP;

        return [
            $dumper,
            $var,
            $dump,
        ];
    }

    private function getArrayWithObject()
    {
        $dumper = new LightVarDumper(true);
        $object = new \stdClass();
        $object->foo = 'bar';
        $objectId = HasherFactory::create()->getHashId($object);
        $var = [
            1,
            null,
            [
                M_PI,
                $object,
            ],
        ];
        list($file, $line) = $this->getTestFileLine();
        $dump
            = <<<DUMP
{$file}:{$line}:
array(3) {
    [0] => 1
    [1] => NULL
    [2] =>
        array(2) {
            [0] => M_PI
            [1] =>
                object(stdClass) #{$objectId} (1) {
                    \$foo => “bar”
                }
        }
}

DUMP;

        return [
            $dumper,
            $var,
            $dump,
        ];
    }

    private function getSingleLineString()
    {
        $dumper = new LightVarDumper(true);
        $var = ['Hello world!'];
        list($file, $line) = $this->getTestFileLine();
        $dump = "{$file}:{$line}:\narray(1) {“Hello world!”}\n";

        return [$dumper, $var, $dump];
    }

    private function getTestFileLine()
    {
        $reflection = new \ReflectionClass('Awesomite\VarDumper\LightVarDumperTest');

        return [
            $reflection->getFileName(),
            75,
        ];
    }
}
