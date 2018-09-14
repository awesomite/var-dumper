<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Properties;

use Awesomite\VarDumper\BaseTestCase;

/**
 * @internal
 */
final class VarPropertyTest extends BaseTestCase
{
    /**
     * @dataProvider             providerInvalidConstructor
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid value of $visibility!
     */
    public function testInvalidConstructor()
    {
        $reflection = new \ReflectionClass('Awesomite\VarDumper\Properties\VarProperty');
        $reflection->newInstanceArgs(\func_get_args());
    }

    public function providerInvalidConstructor()
    {
        return array(
            array('name', 'value', false, \get_class($this)),
            array('name', 'value', new \stdClass(), \get_class($this)),
        );
    }
}
