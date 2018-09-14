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
final class ProviderMaxStringLength implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array(
            array(5, 'Hello world!', "string(12) “Hello”...\n"),
            array(6, 'Hello world!', "string(12) “Hello ”...\n"),
            array(12, 'Hello world!', "“Hello world!”\n"),
            array(13, 'Hello world!', "“Hello world!”\n"),
        );

        return new \ArrayIterator($result);
    }
}
