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

/**
 * @internal
 */
class ProviderIndent implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = [
            'dashes' => $this->getDashes(),
            'dots'   => $this->getDots(),
        ];

        return new \ArrayIterator($result);
    }

    private function getDashes()
    {
        $var = [[[]]];
        $dump
            = <<<'DUMP'
array(1) {
----[0] => array(1) {array(0) {}}
}

DUMP;

        return ['----', $var, $dump];
    }

    private function getDots()
    {
        $var = [[[1, 2, 3]],];
        $dump
            = <<<'DUMP'
array(1) {
..[0] =>
....array(1) {
......[0] => array(3) {1, 2, 3}
....}
}

DUMP;

        return ['..', $var, $dump];
    }
}
