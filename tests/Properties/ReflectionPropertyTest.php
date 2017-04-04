<?php

namespace Awesomite\VarDumper\Properties;

use Awesomite\VarDumper\BaseTestCase;

/**
 * @internal
 */
class ReflectionPropertyTest extends BaseTestCase
{
    private $testProperty;

    /**
     * @dataProvider providerInvalidConstructor
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument $object is not an object!
     */
    public function testInvalidConstructor()
    {
        $reflection = new \ReflectionClass('Awesomite\VarDumper\Properties\ReflectionProperty');
        $reflection->newInstanceArgs(func_get_args());
    }

    public function providerInvalidConstructor()
    {
        $reflection = new \ReflectionProperty($this, 'testProperty');
        return array(
            array($reflection, false),
            array($reflection, 1),
            array($reflection, get_class($this)),
        );
    }

    /**
     * @dataProvider providerGetDeclaringClass
     *
     * @param ReflectionProperty $property
     * @param string $expectedClass
     */
    public function testGetDeclaringClass(ReflectionProperty $property, $expectedClass)
    {
        $this->assertSame($expectedClass, $property->getDeclaringClass());
    }

    public function providerGetDeclaringClass()
    {
        $childClass = get_class(new TestChild());
        $parentClass = get_class(new TestParent());

        return array(
            array(new ReflectionProperty(new \ReflectionProperty($childClass, 'foo'), new TestChild()), $parentClass),
        );
    }
}
