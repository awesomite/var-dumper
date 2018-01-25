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
class PropertiesTest extends BaseTestCase
{
    /**
     * @dataProvider providerGetProperties
     *
     * @param $object
     */
    public function testGetProperties($object)
    {
        $properties = new Properties($object);
        foreach ($properties->getProperties() as $property) {
            $this->assertInstanceOf('Awesomite\VarDumper\Properties\PropertyInterface', $property);
        }
    }

    public function providerGetProperties()
    {
        $obj = new \stdClass();
        $obj->foo = 'bar';

        return [
            [new \stdClass()],
            [$obj],
        ];
    }

    /**
     * @dataProvider providerInvalidConstructor
     * @expectedException \InvalidArgumentException
     *
     * @param $object
     */
    public function testInvalidConstructor($object)
    {
        new Properties($object);
    }

    public function providerInvalidConstructor()
    {
        return [
            [1],
            [false],
            [[]],
            [null],
        ];
    }

    /**
     * @dataProvider providerArrayObject
     *
     * @param \ArrayObject $array
     * @param array        $expectedNames
     */
    public function testArrayObject(\ArrayObject $array, array $expectedNames)
    {
        $properties = new Properties($array);
        $names = [];
        foreach ($properties->getProperties() as $property) {
            /** @var PropertyInterface $property */
            $names[] = $property->getName();
        }
        $this->assertSame(\count($expectedNames), \count($names));
        $diff = \array_diff($names, $expectedNames);
        $this->assertSame(0, \count($diff), 'Diff: "' . \implode('", "', $diff) . '".');
    }

    public function providerArrayObject()
    {
        $object = new ArrayObject();
        $object['test'] = 'hello';
        $object->property = 'value';

        return [
            [$object, ['privateProperty', 'property', 'storage', 'flags', 'iteratorClass']],
        ];
    }

    /**
     * @dataProvider providerClosure
     *
     * @param \Closure            $closure
     * @param PropertyInterface[] $expectedProperties
     */
    public function testClosure(\Closure $closure, array $expectedProperties)
    {
        $propertiesObj = new Properties($closure);
        /** @var PropertyInterface[] $properties */
        $properties = $propertiesObj->getProperties();
        $this->assertSame(\count($properties), \count($expectedProperties));
        $methods = [
            'getName',
            'getValue',
            'getDeclaringClass',
            'isPrivate',
            'isProtected',
            'isPublic',
            'isVirtual',
            'isStatic',
        ];
        foreach ($properties as $key => $value) {
            foreach ($methods as $method) {
                $this->assertSame(
                    \call_user_func([$expectedProperties[$key], $method]),
                    \call_user_func([$value, $method]),
                    \sprintf('%s [getName() === %s]', $method, $value->getName())
                );
            }
        }
    }

    public function providerClosure()
    {
        return [
            $this->getClosureData(),
            $this->getInternalClosureName(),
        ];
    }

    private function getClosureData()
    {
        $fnCreateProperty = function ($name, $value) {
            return new VarProperty($name, $value, VarProperty::VISIBILITY_PUBLIC, 'Closure', false, true);
        };

        $closure = function () {
        };
        $properties = [
            $fnCreateProperty('name', __NAMESPACE__ . '\\{closure}'),
            $fnCreateProperty('filename', __FILE__),
            $fnCreateProperty('startLine', __LINE__ - 5),
            $fnCreateProperty('endLine', __LINE__ - 5),
        ];
        $properties[] = $fnCreateProperty('closureScopeClass', \get_class($this));

        return [
            $closure,
            $properties,
        ];
    }

    private function getInternalClosureName()
    {
        $fnCreateProperty = function ($name, $value) {
            return new VarProperty($name, $value, VarProperty::VISIBILITY_PUBLIC, 'Closure', false, true);
        };

        $closure = \Closure::fromCallable('strpos');
        $properties = [
            $fnCreateProperty('name', 'strpos'),
        ];

        return [
            $closure,
            $properties,
        ];
    }
}
