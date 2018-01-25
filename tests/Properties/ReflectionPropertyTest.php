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
class ReflectionPropertyTest extends BaseTestCase
{
    private $testProperty;

    /**
     * @dataProvider             providerInvalidConstructor
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument $object is not an object!
     */
    public function testInvalidConstructor()
    {
        $reflection = new \ReflectionClass('Awesomite\VarDumper\Properties\ReflectionProperty');
        $reflection->newInstanceArgs(\func_get_args());
    }

    public function providerInvalidConstructor()
    {
        $reflection = new \ReflectionProperty($this, 'testProperty');

        return [
            [$reflection, false],
            [$reflection, 1],
            [$reflection, \get_class($this)],
        ];
    }

    /**
     * @dataProvider providerGetDeclaringClass
     *
     * @param ReflectionProperty $property
     * @param string             $expectedClass
     */
    public function testGetDeclaringClass(ReflectionProperty $property, $expectedClass)
    {
        $this->assertSame($expectedClass, $property->getDeclaringClass());
    }

    public function providerGetDeclaringClass()
    {
        $childClass = \get_class(new TestChild());
        $parentClass = \get_class(new TestParent());

        return [
            [new ReflectionProperty(new \ReflectionProperty($childClass, 'foo'), new TestChild()), $parentClass],
        ];
    }
}
